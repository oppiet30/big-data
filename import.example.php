<?php

$pdo = new PDO(
    'mysql:host=localhost;dbname=bigdb;charset=utf8mb4',
    'user',
    'pass',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	PDO::MYSQL_ATTR_LOCAL_INFILE => true,
    ]
);

$dir = __DIR__ . '/data';
$files = glob($dir . '/chunk_*.csv');
$pdo->exec("SET SESSION foreign_key_checks = 0");
$pdo->exec("SET SESSION unique_checks = 0");
$pdo->exec("ALTER TABLE big_table DROP INDEX idx_sample");
foreach ($files as $file) {
    $fileEscaped = addslashes($file);
    $sql = "
        LOAD DATA LOCAL INFILE '{$fileEscaped}'
        INTO TABLE big_table
        FIELDS TERMINATED BY ','
        ENCLOSED BY '\"'
        LINES TERMINATED BY '\n'
        (gene_id, sample_id, value, created_at)
    ";

    $start = microtime(true);
    $pdo->beginTransaction();
    $pdo->exec($sql);
    $pdo->commit();
    $elapsed = microtime(true) - $start;

    echo "Loaded {$file} in {$elapsed} seconds\n";
}

$start = microtime(true);
$pdo->exec("ALTER TABLE big_table ADD INDEX idx_sample (sample_id)");
$elapsed = microtime(true) - $start;
echo "Rebuilt idx_sample in {$elapsed} seconds\n";
