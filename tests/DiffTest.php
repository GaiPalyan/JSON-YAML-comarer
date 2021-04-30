<?php

namespace Test;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DiffTest extends TestCase
{
    private string $filePathJSON1 = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures/JSON1.json';
    private string $filePathJSON2 = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures/JSON2.json';
    private string $filePathYaml1 = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures/YAML1.yaml';
    private string $filePathYaml2 = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures/YAML2.yaml';
    private string $diffMapPath = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures/DiffMap';
    private string $diffMap;

    public function setUp(): void
    {
        $this->diffMap = file_get_contents($this->diffMapPath);
    }

    public function testDiff()
    {
        $actualJsonDiff = genDiff($this->filePathJSON1, $this->filePathJSON2);
        $actualYamlDiff = genDiff($this->filePathYaml1, $this->filePathYaml2);

        $this->assertEquals($this->diffMap, $actualJsonDiff);
        $this->assertEquals($this->diffMap, $actualYamlDiff);
    }
}
