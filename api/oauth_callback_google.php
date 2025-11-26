<?php
session_start();
require_once __DIR__ . '/oauth_config.php';
require_once __DIR__ . '/db.php';

// Basic callback handler for Google OAuth
if (isset($_GET['error'])) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<p>OAuth error: ' . htmlspecialchars($_GET['error']) . '</p>';
    exit;
}

if (empty($_GET['code']) || empty($_GET['state']) || !isset($_SESSION['oauth2state_google']) || $_GET['state'] !== $_SESSION['oauth2state_google']) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<p>Invalid OAuth state. Please try again.</p>';
    exit;
}

$code = $_GET['code'];

// Exchange authorization code for access token
$tokenUrl = 'https://oauth2.googleapis.com/token';
$post = http_build_query([
    'code' => $code,
    'client_id' => $GOOGLE_CLIENT_ID,
    'client_secret' => $GOOGLE_CLIENT_SECRET,
    'redirect_uri' => $GOOGLE_REDIRECT,
    'grant_type' => 'authorization_code'
]);

$ch = curl_init($tokenUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$tokenResp = curl_exec($ch);
if ($tokenResp === false) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<p>Token exchange failed: ' . htmlspecialchars(curl_error($ch)) . '</p>';
    exit;
}
curl_close($ch);

$tok = json_decode($tokenResp, true);
if (empty($tok['access_token'])) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<p>Invalid token response</p><pre>' . htmlspecialchars($tokenResp) . '</pre>';
    exit;
}

$access_token = $tok['access_token'];

// Fetch userinfo
$ui = curl_init('https://www.googleapis.com/oauth2/v3/userinfo');
curl_setopt($ui, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ui, CURLOPT_HTTPHEADER, ["Authorization: Bearer $access_token"]);
$uiResp = curl_exec($ui);
if ($uiResp === false) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<p>Failed fetching userinfo: ' . htmlspecialchars(curl_error($ui)) . '</p>';
    exit;
}
curl_close($ui);

$userInfo = json_decode($uiResp, true);
if (empty($userInfo['email'])) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<p>Could not retrieve email from provider.</p>';
    exit;
}

$email = $userInfo['email'];
$name = $userInfo['name'] ?? ($userInfo['given_name'] ?? $email);

// Find or create user
$stmt = $conn->prepare('SELECT id, name, email FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
    $user = ['id' => $row['id'], 'name' => $row['name'], 'email' => $row['email']];
} else {
    $ins = $conn->prepare('INSERT INTO users (name, email) VALUES (?, ?)');
    $ins->bind_param('ss', $name, $email);
    $ins->execute();
    // compatible last insert id for SQLite wrapper or mysqli
    if (is_callable([$conn, 'lastInsertId'])) {
      $id = $conn->lastInsertId();
    } elseif (isset($conn->insert_id)) {
      $id = $conn->insert_id;
    } else {
      $id = null;
    }
    $user = ['id' => $id, 'name' => $name, 'email' => $email];
}

// Create a simple token (for production use proper session/JWT)
$token = bin2hex(random_bytes(16));
// Return a small HTML page that posts the token back to the opener window and closes.
// Persist session token
$created_at = date('Y-m-d H:i:s');
if ($ins = $conn->prepare('INSERT INTO sessions (user_id, token, created_at) VALUES (?, ?, ?)')) {
  $ins->bind_param('iss', $user['id'], $token, $created_at);
  $ins->execute();
}

// Return a small HTML page that posts the token back to the opener window and closes.
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Signing in…</title></head>
<body>
<script>
  (function(){
    const payload = {
      token: <?php echo json_encode($token); ?>,
      user: <?php echo json_encode($user); ?>
    };
    try {
      if (window.opener && window.opener.location.origin === window.location.origin) {
        window.opener.postMessage(payload, window.location.origin);
        window.close();
      } else {
        // Fallback: write JSON for manual copy
        document.body.innerText = JSON.stringify(payload, null, 2);
      }
    } catch(e) {
      document.body.innerText = 'Sign in completed. You can close this window.';
    }
  })();
</script>
</body></html>
