<?php

declare(strict_types=1);

namespace Test;

use Exception;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DiffTest extends TestCase
{
    /**
     * @param string $file
     * @return string
     */
    public function getPath(string $file): string
    {
        $path =  __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $file;
        return realpath($path);
    }

    /**
     * @return array
     */
    public function additionProvider(): array
    {
        return [
             'complex json --stylish' => [
                 'stylish',
                 'complex1.json',
                 'complex2.json',
                 'stylish'
             ],
             'complex yml --stylish' => [
                'stylish',
                'complex1.yaml',
                'complex2.yaml',
                'stylish'
             ],
             'complex json --plain' => [
                'plain',
                'complex1.json',
                'complex2.json',
                'plain'
             ],
             'complex yml --plain' => [
                'plain',
                'complex1.yaml',
                'complex2.yaml',
                'plain'
             ],
             'complex json --json' => [
                'json',
                'complex1.json',
                'complex2.json',
                'json'
             ],
             'complex yml --json' => [
                'json',
                'complex1.yaml',
                'complex2.yaml',
                'json'
             ]
        ];
    }

    /**
     * @param string $expected
     * @param string $firstFile
     * @param string $secondFile
     * @param string $format
     * @dataProvider additionProvider
     * @throws Exception
     */
    public function testDiff(
        string $expected,
        string $firstFile,
        string $secondFile,
        string $format = 'stylish'
    ) {
        $firstFile = $this->getPath($firstFile);
        $secondFile = $this->getPath($secondFile);
        $expected = file_get_contents($this->getPath($expected));
        $this->assertEquals($expected, genDiff($firstFile, $secondFile, $format));
    }
}
