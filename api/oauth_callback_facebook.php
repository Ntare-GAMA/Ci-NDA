<?php
session_start();
require_once __DIR__ . '/oauth_config.php';
require_once __DIR__ . '/db.php';

if (isset($_GET['error'])) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<p>OAuth error: ' . htmlspecialchars($_GET['error']) . '</p>';
    exit;
}

if (empty($_GET['code']) || empty($_GET['state']) || !isset($_SESSION['oauth2state_facebook']) || $_GET['state'] !== $_SESSION['oauth2state_facebook']) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<p>Invalid OAuth state. Please try again.</p>';
    exit;
}

$code = $_GET['code'];

// Exchange code for access token
$tokenUrl = 'https://graph.facebook.com/v17.0/oauth/access_token?'.http_build_query([
    'client_id' => $FACEBOOK_CLIENT_ID,
    'redirect_uri' => $FACEBOOK_REDIRECT,
    'client_secret' => $FACEBOOK_CLIENT_SECRET,
    'code' => $code
]);

$resp = file_get_contents($tokenUrl);
if ($resp === false) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<p>Token exchange failed</p>';
    exit;
}

$tok = json_decode($resp, true);
if (empty($tok['access_token'])) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<p>Invalid token response</p><pre>' . htmlspecialchars($resp) . '</pre>';
    exit;
}

$access_token = $tok['access_token'];

// Get user info
$uiUrl = 'https://graph.facebook.com/me?fields=id,name,email&access_token='.urlencode($access_token);
$uiResp = file_get_contents($uiUrl);
if ($uiResp === false) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<p>Failed fetching userinfo</p>';
    exit;
}

$userInfo = json_decode($uiResp, true);
if (empty($userInfo['email'])) {
    // Facebook may not return email if not available/permission not granted
    header('Content-Type: text/html; charset=utf-8');
    echo '<p>Could not retrieve email from Facebook. Please ensure your Facebook account has a verified email.</p>';
    exit;
}

$email = $userInfo['email'];
$name = $userInfo['name'] ?? $email;

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

// Create token
$token = bin2hex(random_bytes(16));
// Post token back to opener
// Persist session token
$created_at = date('Y-m-d H:i:s');
if ($ins = $conn->prepare('INSERT INTO sessions (user_id, token, created_at) VALUES (?, ?, ?)')) {
  $ins->bind_param('iss', $user['id'], $token, $created_at);
  $ins->execute();
}

// Post token back to opener
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
        document.body.innerText = JSON.stringify(payload, null, 2);
      }
    } catch(e) {
      document.body.innerText = 'Sign in completed. You can close this window.';
    }
  })();
</script>
</body></html>
