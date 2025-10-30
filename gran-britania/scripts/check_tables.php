<?php
$dbPath = __DIR__ . '/../database/database.sqlite';
if (!file_exists($dbPath)) {
    echo "SQLite DB not found at: $dbPath\n";
    exit(1);
}
try {
    $db = new PDO('sqlite:' . $dbPath);
    $tables = ['posts', 'testimonials'];
    foreach ($tables as $t) {
        try {
            $stmt = $db->query("SELECT COUNT(*) FROM $t");
            $count = $stmt ? (int)$stmt->fetchColumn() : '(error)';
        } catch (Exception $e) {
            $count = '(no table)';
        }
        echo "$t: $count\n";
    }
} catch (Exception $e) {
    echo "DB error: " . $e->getMessage() . "\n";
    exit(1);
}
