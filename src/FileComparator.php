<?php
declare(strict_types=1);

namespace TestAssignment;

class FileComparator
{
    public function compareFiles(string $file1, string $file2): void
    {
        $array1 = file($file1, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $array2 = file($file2, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $output1 = array_diff($array1, $array2);
        $output2 = array_diff($array2, $array1);
        $output1Content = implode("\n", $output1);
        $output2Content = implode("\n", $output2);
        if ($output1Content !== '') {
            $output1Content .= "\n";
        }
        if ($output2Content !== '') {
            $output2Content .= "\n";
        }
        file_put_contents('output1.txt', $output1Content);
        file_put_contents('output2.txt', $output2Content);
    }
}
