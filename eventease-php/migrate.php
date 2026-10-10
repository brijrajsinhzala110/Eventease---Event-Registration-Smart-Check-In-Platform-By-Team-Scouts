<?php
require_once __DIR__ . '/config/database.php';

echo "Running migrations...\n";

// Ensure migrations table exists
$pdo->exec("CREATE TABLE IF NOT EXISTS schema_migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL UNIQUE,
    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$migrations_dir = __DIR__ . '/database/migrations';
$files = glob($migrations_dir . '/*.sql');
sort($files);

foreach ($files as $file) {
    $filename = basename($file);
    
    // Check if executed
    $stmt = $pdo->prepare("SELECT id FROM schema_migrations WHERE filename = ?");
    $stmt->execute([$filename]);
    if ($stmt->fetch()) {
        continue; // Already run
    }
    
    echo "Executing $filename...\n";
    $sql = file_get_contents($file);
    
    try {
        $pdo->exec($sql);
        $stmt = $pdo->prepare("INSERT INTO schema_migrations (filename) VALUES (?)");
        $stmt->execute([$filename]);
        echo "Successfully executed $filename.\n";
    } catch (PDOException $e) {
        echo "Error executing $filename: " . $e->getMessage() . "\n";
        // Stop on first failure
        break;
    }
}
echo "Migrations complete.\n";
