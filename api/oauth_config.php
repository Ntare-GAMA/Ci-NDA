<?php
// OAuth configuration: use environment variables when possible.
// For local development set these env vars or replace the placeholders below.

$GOOGLE_CLIENT_ID = getenv('GOOGLE_CLIENT_ID') ?: 'YOUR_GOOGLE_CLIENT_ID';
$GOOGLE_CLIENT_SECRET = getenv('GOOGLE_CLIENT_SECRET') ?: 'YOUR_GOOGLE_CLIENT_SECRET';
$GOOGLE_REDIRECT = getenv('GOOGLE_REDIRECT') ?: 'http://localhost:8000/api/oauth_callback_google.php';

$FACEBOOK_CLIENT_ID = getenv('FACEBOOK_CLIENT_ID') ?: 'YOUR_FACEBOOK_CLIENT_ID';
$FACEBOOK_CLIENT_SECRET = getenv('FACEBOOK_CLIENT_SECRET') ?: 'YOUR_FACEBOOK_CLIENT_SECRET';
$FACEBOOK_REDIRECT = getenv('FACEBOOK_REDIRECT') ?: 'http://localhost:8000/api/oauth_callback_facebook.php';

// You can change the default redirect URLs above if you run the PHP server on a different port.

?>
