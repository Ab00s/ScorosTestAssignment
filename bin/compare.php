<?php
declare(strict_types=1);

use TestAssignment\FileComparator;

require __DIR__ . '/../vendor/autoload.php';


if ($argc < 3) {
    exit("Usage: php compare file1.txt file2.txt\n");
}

$comparator = new FileComparator();
$comparator->compareFiles($argv[1], $argv[2]);
