<?php

$pdo = new PDO(
    'mysql:host=localhost;dbname=bigdb;charset=utf8mb4',
    'user',
    'pass',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]
);

$dir = __DIR__ . '/data';
$files = glob($dir . '/chunk_*.csv');

foreach ($files as $file) {
    $fileEscaped = addslashes($file);
    $sql = "
        LOAD DATA INFILE '{$fileEscaped}'
        INTO TABLE big_table
        FIELDS TERMINATED BY ','
        ENCLOSED BY '\"'
        LINES TERMINATED BY '\n'
        (gene_id, sample_id, value, created_at)
    ";

    $start = microtime(true);
    $pdo->exec($sql);
    $elapsed = microtime(true) - $start;

    echo "Loaded {$file} in {$elapsed} seconds\n";
}

