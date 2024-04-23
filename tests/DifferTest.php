<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Gendiff\Differ\genDiff;

function getFixturePath($fileName)
{
    $fileNameParts = [__DIR__, 'fixtures', $fileName];
    return implode('/', $fileNameParts);
}

class DifferTest extends TestCase
{
    public function testGenDiffJSON(): void
    {
        $this->assertStringEqualsFile(getFixturePath("sampleString1.txt"), genDiff("file1.json", "file2.json"));
    }

    public function testGenDiffYML(): void
    {
        $this->assertStringEqualsFile(getFixturePath("sampleString1.txt"), genDiff("file1.yml", "file2.yml"));
    }
}
