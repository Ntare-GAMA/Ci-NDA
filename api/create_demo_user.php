<?php
// Usage: php create_demo_user.php name email password
require_once __DIR__ . '/db.php';

$name = $argv[1] ?? 'filmmaker';
$email = $argv[2] ?? 'filmmaker@cinda.com';
$password = $argv[3] ?? 'filmmaker123';

if (!$email || !$password) {
    fwrite(STDERR, "email and password required\n");
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare('INSERT INTO users (name, email, password_hash, created_at) VALUES (?, ?, ?, NOW())');
if (!$stmt) {
    fwrite(STDERR, "Prepare failed: " . $conn->error . "\n");
    exit(1);
}

$stmt->bind_param('sss', $name, $email, $hash);
if ($stmt->execute()) {
    echo "Inserted user id: " . $conn->insert_id . PHP_EOL;
    exit(0);
} else {
    fwrite(STDERR, "Insert failed: " . $stmt->error . "\n");
    exit(1);
}
