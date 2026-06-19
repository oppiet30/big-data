<?php

$rowsPerFile = 5_000_000;      // 5 million rows per file
$totalRows   = 1_000_000_000;  // 1 billion
$files       = intdiv($totalRows, $rowsPerFile);

$outDir = __DIR__ . '/data';
if (!is_dir($outDir)) {
    mkdir($outDir, 0777, true);
}

for ($f = 0; $f < $files; $f++) {
    $filename = $outDir . '/chunk_' . str_pad($f, 4, '0', STR_PAD_LEFT) . '.csv';
    $fh = fopen($filename, 'w');
    if (!$fh) {
        throw new RuntimeException("Cannot open $filename");
    }

    for ($i = 0; $i < $rowsPerFile; $i++) {
        $rowId = $f * $rowsPerFile + $i;

        $geneId   = 'ENSG' . str_pad((string)($rowId % 1_000_000), 11, '0', STR_PAD_LEFT);
        $sampleId = $rowId % 10_000;
        $value    = mt_rand(0, 1_000_000) / 1000;
        $created  = date('Y-m-d H:i:s');

        fputcsv($fh, [$geneId, $sampleId, $value, $created]);
    }

    fclose($fh);
    echo "Generated $filename\n";
}

