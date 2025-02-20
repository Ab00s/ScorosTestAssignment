<?php
declare(strict_types=1);

namespace TestAssignment\Tests;

use PHPUnit\Framework\TestCase;
use TestAssignment\FileComparator;

class FileComparatorTest extends TestCase
{
    protected function setUp(): void
    {
        $dir = sys_get_temp_dir() . '/file_comparator_test';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }

    protected function tearDown(): void
    {
        $dir = sys_get_temp_dir() . '/file_comparator_test';
        if (is_dir($dir)) {
            array_map('unlink', glob($dir . '/*'));
            rmdir($dir);
        }
        if (file_exists('output1.txt')) {
            unlink('output1.txt');
        }
        if (file_exists('output2.txt')) {
            unlink('output2.txt');
        }
    }

    public function testCompareFiles(): void
    {
        $tempDir = sys_get_temp_dir() . '/file_comparator_test';
        $file1 = $tempDir . '/file1.txt';
        $file2 = $tempDir . '/file2.txt';
        file_put_contents($file1, "a\nb\nc\n");
        file_put_contents($file2, "b\nc\nd\n");
        $comparator = new FileComparator();
        $comparator->compareFiles($file1, $file2);
        $this->assertEquals("a\n", file_get_contents('output1.txt'));
        $this->assertEquals("d\n", file_get_contents('output2.txt'));
    }

    public function testEmptyFiles(): void
    {
        $tempDir = sys_get_temp_dir() . '/file_comparator_test';
        $file1 = $tempDir . '/empty1.txt';
        $file2 = $tempDir . '/empty2.txt';
        file_put_contents($file1, "");
        file_put_contents($file2, "");
        $comparator = new FileComparator();
        $comparator->compareFiles($file1, $file2);
        $this->assertEquals("", file_get_contents('output1.txt'));
        $this->assertEquals("", file_get_contents('output2.txt'));
    }

    public function testIdenticalFiles(): void
    {
        $tempDir = sys_get_temp_dir() . '/file_comparator_test';
        $file1 = $tempDir . '/identical1.txt';
        $file2 = $tempDir . '/identical2.txt';
        $content = "a\nb\nc\n";
        file_put_contents($file1, $content);
        file_put_contents($file2, $content);
        $comparator = new FileComparator();
        $comparator->compareFiles($file1, $file2);
        $this->assertEquals("", file_get_contents('output1.txt'));
        $this->assertEquals("", file_get_contents('output2.txt'));
    }

    public function testNonOverlappingFiles(): void
    {
        $tempDir = sys_get_temp_dir() . '/file_comparator_test';
        $file1 = $tempDir . '/nonoverlap1.txt';
        $file2 = $tempDir . '/nonoverlap2.txt';
        file_put_contents($file1, "a\nb\n");
        file_put_contents($file2, "c\nd\n");
        $comparator = new FileComparator();
        $comparator->compareFiles($file1, $file2);
        $this->assertEquals("a\nb\n", file_get_contents('output1.txt'));
        $this->assertEquals("c\nd\n", file_get_contents('output2.txt'));
    }

    public function testDuplicateLines(): void
    {
        $tempDir = sys_get_temp_dir() . '/file_comparator_test';
        $file1 = $tempDir . '/duplicate1.txt';
        $file2 = $tempDir . '/duplicate2.txt';
        file_put_contents($file1, "a\na\nb\n");
        file_put_contents($file2, "a\n");
        $comparator = new FileComparator();
        $comparator->compareFiles($file1, $file2);
        $this->assertEquals("b\n", file_get_contents('output1.txt'));
        $this->assertEquals("", file_get_contents('output2.txt'));
    }
}
