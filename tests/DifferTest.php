<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;
use function Differ\Helpers\getFixturePath;

class DifferTest extends TestCase
{
    public function testStylish(): void
    {
        $expected = file_get_contents(getFixturePath("stylishResult.txt"));
        $this->assertEquals($expected, genDiff("file1.json", "file2.json"));
        $this->assertEquals($expected, genDiff("file1.yml", "file2.yml"));

        $expected = file_get_contents(getFixturePath("stylishResultNested.txt"));
        $this->assertEquals($expected, genDiff("file3.json", "file4.json"));
        $this->assertEquals($expected, genDiff("file3.yml", "file4.yml"));
    }

    public function testPlain(): void
    {
        $expected = file_get_contents(getFixturePath("plainResult.txt"));
        $this->assertEquals($expected, genDiff("file1.json", "file2.json", 'plain'));
        $this->assertEquals($expected, genDiff("file1.yml", "file2.yml", 'plain'));

        $expected = file_get_contents(getFixturePath("plainResultNested.txt"));
        $this->assertEquals($expected, genDiff("file3.json", "file4.json", 'plain'));
        $this->assertEquals($expected, genDiff("file3.yml", "file4.yml", 'plain'));
    }

    public function testJson(): void
    {
        $expected = file_get_contents(getFixturePath("jsonResult.txt"));
        $this->assertEquals($expected, genDiff("file1.json", "file2.json", 'json'));
        $this->assertEquals($expected, genDiff("file1.yml", "file2.yml", 'json'));

        $expected = file_get_contents(getFixturePath("jsonResultNested.txt"));
        $this->assertEquals($expected, genDiff("file3.json", "file4.json", 'json'));
        $this->assertEquals($expected, genDiff("file3.yml", "file4.yml", 'json'));
    }
}
