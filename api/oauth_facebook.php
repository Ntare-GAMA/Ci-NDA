<?php
session_start();
require_once __DIR__ . '/oauth_config.php';

$state = bin2hex(random_bytes(8));
$_SESSION['oauth2state_facebook'] = $state;

$params = http_build_query([
    'client_id' => $FACEBOOK_CLIENT_ID,
    'redirect_uri' => $FACEBOOK_REDIRECT,
    'state' => $state,
    'scope' => 'email,public_profile'
]);

header('Location: https://www.facebook.com/v17.0/dialog/oauth?' . $params);
exit;

?>
