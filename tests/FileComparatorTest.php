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

    // Multi-line input tests

    public function testMultiLineInput(): void
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

    // Single-line input tests

    public function testSingleLineInput(): void
    {
        $tempDir = sys_get_temp_dir() . '/file_comparator_test';
        $file1 = $tempDir . '/single1.txt';
        $file2 = $tempDir . '/single2.txt';
        file_put_contents($file1, "abcd");
        file_put_contents($file2, "bcde");
        $comparator = new FileComparator();
        $comparator->compareFiles($file1, $file2);
        $this->assertEquals("a\n", file_get_contents('output1.txt'));
        $this->assertEquals("e\n", file_get_contents('output2.txt'));
    }

    public function testSingleLineInputIdentical(): void
    {
        $tempDir = sys_get_temp_dir() . '/file_comparator_test';
        $file1 = $tempDir . '/identical_single1.txt';
        $file2 = $tempDir . '/identical_single2.txt';
        file_put_contents($file1, "abc");
        file_put_contents($file2, "abc");
        $comparator = new FileComparator();
        $comparator->compareFiles($file1, $file2);
        $this->assertEquals("", file_get_contents('output1.txt'));
        $this->assertEquals("", file_get_contents('output2.txt'));
    }

    public function testSingleLineInputExtraCharacterFile1(): void
    {
        $tempDir = sys_get_temp_dir() . '/file_comparator_test';
        $file1 = $tempDir . '/extra1.txt';
        $file2 = $tempDir . '/extra2.txt';
        file_put_contents($file1, "abcdef");
        file_put_contents($file2, "abcde");
        $comparator = new FileComparator();
        $comparator->compareFiles($file1, $file2);
        $this->assertEquals("f\n", file_get_contents('output1.txt'));
        $this->assertEquals("", file_get_contents('output2.txt'));
    }

    public function testSingleLineInputExtraCharacterFile2(): void
    {
        $tempDir = sys_get_temp_dir() . '/file_comparator_test';
        $file1 = $tempDir . '/extra3.txt';
        $file2 = $tempDir . '/extra4.txt';
        file_put_contents($file1, "abcde");
        file_put_contents($file2, "abcdef");
        $comparator = new FileComparator();
        $comparator->compareFiles($file1, $file2);
        $this->assertEquals("", file_get_contents('output1.txt'));
        $this->assertEquals("f\n", file_get_contents('output2.txt'));
    }

    public function testSingleLineInputWithDuplicates(): void
    {
        $tempDir = sys_get_temp_dir() . '/file_comparator_test';
        $file1 = $tempDir . '/dup1.txt';
        $file2 = $tempDir . '/dup2.txt';
        file_put_contents($file1, "aabbc");
        file_put_contents($file2, "abc");
        $comparator = new FileComparator();
        $comparator->compareFiles($file1, $file2);
        $this->assertEquals("", file_get_contents('output1.txt'));
        $this->assertEquals("", file_get_contents('output2.txt'));
    }

    // Edge cases with symbols, numbers, and mixed characters

    public function testSingleLineSymbolsEdgeCase(): void
    {
        $tempDir = sys_get_temp_dir() . '/file_comparator_test';
        $file1 = $tempDir . '/symbols1.txt';
        $file2 = $tempDir . '/symbols2.txt';
        file_put_contents($file1, "!@#$");
        file_put_contents($file2, "!@%&");
        $comparator = new FileComparator();
        $comparator->compareFiles($file1, $file2);
        $this->assertEquals("#\n$\n", file_get_contents('output1.txt'));
        $this->assertEquals("%\n&\n", file_get_contents('output2.txt'));
    }

    public function testSingleLineNumbersEdgeCase(): void
    {
        $tempDir = sys_get_temp_dir() . '/file_comparator_test';
        $file1 = $tempDir . '/numbers1.txt';
        $file2 = $tempDir . '/numbers2.txt';
        file_put_contents($file1, "12345");
        file_put_contents($file2, "12367");
        $comparator = new FileComparator();
        $comparator->compareFiles($file1, $file2);
        $this->assertEquals("4\n5\n", file_get_contents('output1.txt'));
        $this->assertEquals("6\n7\n", file_get_contents('output2.txt'));
    }

    public function testSingleLineMixedEdgeCase(): void
    {
        $tempDir = sys_get_temp_dir() . '/file_comparator_test';
        $file1 = $tempDir . '/mixed1.txt';
        $file2 = $tempDir . '/mixed2.txt';
        file_put_contents($file1, "a1!b");
        file_put_contents($file2, "1!a2");
        $comparator = new FileComparator();
        $comparator->compareFiles($file1, $file2);
        $this->assertEquals("b\n", file_get_contents('output1.txt'));
        $this->assertEquals("2\n", file_get_contents('output2.txt'));
    }
}
