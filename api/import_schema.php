<?php
// Simple importer for schema.sql when mysql CLI isn't available.
// Usage: php import_schema.php --host=localhost --user=root --pass=secret --file=schema.sql

$opts = getopt('', ['host::', 'user::', 'pass::', 'file::']);
$host = $opts['host'] ?? '127.0.0.1';
$user = $opts['user'] ?? 'root';
$pass = $opts['pass'] ?? '';
$file = $opts['file'] ?? __DIR__ . '/schema.sql';

if (!file_exists($file)) {
    fwrite(STDERR, "Schema file not found: $file\n");
    exit(1);
}

$sql = file_get_contents($file);
if ($sql === false) {
    fwrite(STDERR, "Failed to read schema file\n");
    exit(1);
}

$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_error) {
    fwrite(STDERR, "Connect error ({$mysqli->connect_errno}): {$mysqli->connect_error}\n");
    exit(1);
}
$mysqli->set_charset('utf8mb4');

echo "Importing schema from $file to {$host} as {$user}...\n";

if (!$mysqli->multi_query($sql)) {
    fwrite(STDERR, "Import failed: " . $mysqli->error . "\n");
    $mysqli->close();
    exit(1);
}

do {
    if ($res = $mysqli->store_result()) {
        $res->free();
    }
} while ($mysqli->more_results() && $mysqli->next_result());

if ($mysqli->errno) {
    fwrite(STDERR, "Completed with warnings/errors: {$mysqli->error}\n");
    $mysqli->close();
    exit(1);
}

$mysqli->close();
echo "Schema import completed successfully.\n";
exit(0);
