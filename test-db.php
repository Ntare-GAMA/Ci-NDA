<?php
// Simple diagnostic test
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<style>body{font-family:sans-serif;padding:20px;background:#1a1a1a;color:#fff;}h2,h3{color:#4CAF50;}p{margin:10px 0;}.error{color:#f44336;}.success{color:#4CAF50;}pre{background:#333;padding:10px;overflow:auto;}</style>";
echo "<h2>Database Connection Test</h2>";

echo "<h3>Step 1: Check if api/db.php exists</h3>";
if (file_exists('api/db.php')) {
    echo "<p class='success'>✓ api/db.php found</p>";
    
    echo "<h3>Step 2: Read api/db.php content</h3>";
    $content = file_get_contents('api/db.php');
    echo "<pre>" . htmlspecialchars(substr($content, 0, 500)) . "...</pre>";
    
    echo "<h3>Step 3: Try to include db.php</h3>";
    
    // Capture any errors
    ob_start();
    try {
        include 'api/db.php';
        $output = ob_get_clean();
        
        if (isset($conn)) {
            echo "<p class='success'>✓ db.php loaded successfully!</p>";
            echo "<p class='success'>✓ \$conn variable exists</p>";
            
            if ($conn instanceof mysqli) {
                echo "<p class='success'>✓ Connected to MySQL!</p>";
                
                $result = $conn->query("SELECT DATABASE() as db");
                if ($result) {
                    $row = $result->fetch_assoc();
                    echo "<p>Database: " . $row['db'] . "</p>";
                }
                
                $tables = $conn->query("SHOW TABLES");
                if ($tables) {
                    echo "<h3>Tables:</h3><ul>";
                    while ($row = $tables->fetch_array()) {
                        echo "<li>" . $row[0] . "</li>";
                    }
                    echo "</ul>";
                }
            } else {
                echo "<p class='error'>✗ \$conn is not a mysqli object</p>";
            }
        } else {
            echo "<p class='error'>✗ \$conn variable not set after including db.php</p>";
        }
        
        if ($output) {
            echo "<h3>Output from db.php:</h3>";
            echo "<pre>" . htmlspecialchars($output) . "</pre>";
        }
        
    } catch (Exception $e) {
        ob_end_clean();
        echo "<p class='error'>✗ Exception: " . $e->getMessage() . "</p>";
        echo "<p class='error'>File: " . $e->getFile() . "</p>";
        echo "<p class='error'>Line: " . $e->getLine() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    
} else {
    echo "<p class='error'>✗ api/db.php not found</p>";
    echo "<p>Looking in: " . realpath('.') . "/api/db.php</p>";
}
?>
