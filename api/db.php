<?php
header('Content-Type: application/json');
// Centralized DB connection for the API
// Supports MySQL (mysqli) and SQLite fallback (no server required).

$DB_DRIVER = getenv('DB_DRIVER') ?: (extension_loaded('mysqli') ? 'mysql' : 'sqlite');
$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';
$DB_NAME = getenv('DB_NAME') ?: 'cinda';
// Optional port for MySQL connections
$DB_PORT = getenv('DB_PORT') ?: 3306;

// helper to send JSON and exit
function json_exit($data, $code = 200) {
  http_response_code($code);
  echo json_encode($data);
  exit;
}

// If configured to use MySQL and mysqli is available try to connect
if ($DB_DRIVER === 'mysql' && extension_loaded('mysqli')) {
  // Use explicit port to avoid socket vs TCP resolution issues on Windows
  $conn = @new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, (int)$DB_PORT);
  if ($conn && !$conn->connect_error) {
    $conn->set_charset('utf8mb4');
    return; // $conn is available to requiring scripts
  }
}

// Fall back to SQLite (no external server)
// We'll expose $conn with a thin mysqli-compatible API implemented over PDO for the queries used by the app.

// Ensure data directory exists
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) @mkdir($dataDir, 0755, true);
$sqliteFile = $dataDir . '/cinda.sqlite';

try {
  $pdo = new PDO('sqlite:' . $sqliteFile);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // use UTF-8
  $pdo->exec("PRAGMA encoding = 'UTF-8';");
} catch (Exception $e) {
  json_exit(['error' => 'Failed to open SQLite DB: ' . $e->getMessage()], 500);
}

// Create minimal tables if they don't exist (compatible subset)
$initSql = [
  "CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT,
    created_at DATETIME DEFAULT (datetime('now'))
  );",

  "CREATE TABLE IF NOT EXISTS courses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    instructor TEXT,
    category TEXT,
    duration TEXT,
    level TEXT,
    description TEXT,
    image_url TEXT,
    created_at DATETIME DEFAULT (datetime('now'))
  );",

  "CREATE TABLE IF NOT EXISTS enrollments (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    course_id INTEGER,
    created_at DATETIME DEFAULT (datetime('now'))
  );",

  "CREATE TABLE IF NOT EXISTS mentors (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    title TEXT,
    bio TEXT,
    specialties TEXT,
    years_mentoring INTEGER DEFAULT 0,
    mentees_count INTEGER DEFAULT 0,
    spots_left INTEGER DEFAULT 0,
    avatar_url TEXT
  );",

  "CREATE TABLE IF NOT EXISTS opportunities (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT,
    org TEXT,
    type TEXT,
    description TEXT,
    funding TEXT,
    location TEXT,
    deadline DATE
  );",

  "CREATE TABLE IF NOT EXISTS portfolios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    owner TEXT NOT NULL,
    description TEXT,
    category TEXT,
    tags TEXT,
    views INTEGER DEFAULT 0,
    likes INTEGER DEFAULT 0,
    thumbnail_url TEXT,
    created_at DATETIME DEFAULT (datetime('now'))
  );",
  "CREATE TABLE IF NOT EXISTS sessions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    token TEXT NOT NULL,
    created_at DATETIME DEFAULT (datetime('now'))
  );",
];

foreach ($initSql as $s) {
  $pdo->exec($s);
}

// Compatibility wrappers: provide $conn with ->prepare() and ->query() that emulate the methods used in the codebase
class SQLiteStmtCompat {
  private $pdo;
  private $sql;
  private $bound = [];
  private $stmt;
  public function __construct($pdo, $sql) { $this->pdo = $pdo; $this->sql = $sql; }
  public function bind_param($types /* ignored */, &...$vars) { $this->bound = $vars; }
  public function execute() {
    $this->stmt = $this->pdo->prepare($this->sql);
    $params = [];
    foreach ($this->bound as $v) $params[] = $v;
    $ok = $this->stmt->execute($params);
    return $ok;
  }
  public function get_result() {
    return new SQLiteResultCompat($this->stmt);
  }
  public function error() { return $this->stmt ? implode(' | ', $this->stmt->errorInfo()) : ''; }
}

class SQLiteResultCompat {
  private $stmt;
  public function __construct($stmt) { $this->stmt = $stmt; }
  public function fetch_assoc() { $r = $this->stmt->fetch(PDO::FETCH_ASSOC); return $r === false ? null : $r; }
  public function fetch() { return $this->fetch_assoc(); }
}

class SQLiteConnCompat {
  private $pdo;
  public function __construct($pdo) { $this->pdo = $pdo; }
  public function prepare($sql) { return new SQLiteStmtCompat($this->pdo, $sql); }
  public function query($sql) { $stmt = $this->pdo->query($sql); return new SQLiteResultCompat($stmt); }
  public function set_charset($cs) { /* noop */ }
  public function close() { /* noop */ }
  public function lastInsertId() { return $this->pdo->lastInsertId(); }
}

$conn = new SQLiteConnCompat($pdo);

// make $conn available to including scripts
return;

?>
