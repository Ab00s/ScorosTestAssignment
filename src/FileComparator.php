<?php
declare(strict_types=1);

namespace TestAssignment;

class FileComparator
{
    public function compareFiles(string $file1, string $file2): void
    {
        $lines1 = file($file1, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lines2 = file($file2, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (count($lines1) === 1) {
            $lines1 = str_split(trim($lines1[0]));
        }
        if (count($lines2) === 1) {
            $lines2 = str_split(trim($lines2[0]));
        }
        $output1 = array_diff($lines1, $lines2);
        $output2 = array_diff($lines2, $lines1);
        $content1 = $output1 !== [] ? implode("\n", $output1) . "\n" : '';
        $content2 = $output2 !== [] ? implode("\n", $output2) . "\n" : '';
        file_put_contents('output1.txt', $content1);
        file_put_contents('output2.txt', $content2);
    }
}
