<?php

namespace Test;

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
        return __DIR__ . '/fixtures/' . $file;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function getData(string $fileName): string
    {
        return file_get_contents($this->getPath($fileName));
    }

    /**
     * @return array
     */
    public function additionProvider(): array
    {
        return [
             'complex json --stylish' => [
                 'stylish.txt',
                 'complex1.json',
                 'complex2.json',
                 'stylish'
             ],
             'complex yml --stylish' => [
                'stylish.txt',
                'complex1.yaml',
                'complex2.yaml',
                'stylish'
             ],
             'complex json --plain' => [
                'plain.txt',
                'complex1.json',
                'complex2.json',
                'plain'
             ],
             'complex yml --plain' => [
                'plain.txt',
                'complex1.yaml',
                'complex2.yaml',
                'plain'
             ],
             'complex json --json' => [
                'json.txt',
                'complex1.json',
                'complex2.json',
                'json'
             ],
             'complex yml --json' => [
                'json.txt',
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
     * @throws \Exception
     * @dataProvider additionProvider
     */
    public function testDiff(string $expected, string $firstFile, string $secondFile, string $format = 'stylish')
    {
        $firstFile = $this->getPath($firstFile);
        $secondFile = $this->getPath($secondFile);
        $expected = $this->getData($expected);
        $this->assertEquals($expected, genDiff($firstFile, $secondFile, $format));
    }
}
