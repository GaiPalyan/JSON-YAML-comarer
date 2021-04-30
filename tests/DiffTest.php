<?php

namespace Test;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DiffTest extends TestCase
{
    private $filePathJSON1 = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures/JSON1.json';
    private $filePathJSON2 = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures/JSON2.json';
    private $diffMapPath = __DIR__ . DIRECTORY_SEPARATOR . 'fixtures/DiffMap';
    private $diffMap;

    public function setUp(): void
    {
        $this->diffMap = file_get_contents($this->diffMapPath);
    }

    public function testDiff()
    {
        $actual = genDiff($this->filePathJSON1, $this->filePathJSON2);

        $this->assertEquals($this->diffMap, $actual);
    }
}
