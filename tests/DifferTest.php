<?php

namespace Differ\Tests\DifferTest;

use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function getFixturePath($fileName)
    {
        return __DIR__ . "/fixtures/" . $fileName;
    }

    public static function additionProvider(): mixed
    {
        return [
            ['file3.json', 'file4.json', 'stylish', 'stylishResultNested.txt'],
            ['file3.yml', 'file4.yml', 'stylish', 'stylishResultNested.txt'],
            ['file3.json', 'file4.json', 'plain', 'plainResultNested.txt'],
            ['file3.yml', 'file4.yml', 'plain', 'plainResultNested.txt'],
            ['file3.json', 'file4.json', 'json', 'jsonResultNested.txt'],
            ['file3.yml', 'file4.yml', 'json', 'jsonResultNested.txt'],

            ['file3.json', 'file4.yml', 'stylish', 'stylishResultNested.txt'],
            ['file3.yml', 'file4.json', 'stylish', 'stylishResultNested.txt'],
            ['file3.json', 'file4.yml', 'plain', 'plainResultNested.txt'],
            ['file3.yml', 'file4.json', 'plain', 'plainResultNested.txt'],
            ['file3.json', 'file4.yml', 'json', 'jsonResultNested.txt'],
            ['file3.yml', 'file4.json', 'json', 'jsonResultNested.txt'],
        ];
    }
    /**
     * @throws Exception
     */
    #[DataProvider('additionProvider')]
    public function testDiffer(string $firstFileName, string $secondFileName, string $format, string $expected): void
    {
        $firstFixture = $this->getFixturePath($firstFileName);
        $secondFixture = $this->getFixturePath($secondFileName);
        $result = $this->getFixturePath($expected);

        $this->assertStringEqualsFile($result, genDiff($firstFixture, $secondFixture, $format));
    }
}
