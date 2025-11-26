<?php
session_start();
require_once __DIR__ . '/oauth_config.php';

// Start Google OAuth by redirecting user to Google's OAuth consent screen
$state = bin2hex(random_bytes(8));
$_SESSION['oauth2state_google'] = $state;

$params = http_build_query([
    'client_id' => $GOOGLE_CLIENT_ID,
    'redirect_uri' => $GOOGLE_REDIRECT,
    'response_type' => 'code',
    'scope' => 'openid email profile',
    'state' => $state,
    'access_type' => 'offline',
    'prompt' => 'select_account'
]);

header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . $params);
exit;

?>
