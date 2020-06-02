<?php
namespace Air\Annotation\Test\Locator;

use Air\Annotation\Locator\Scanner;
use PHPUnit\Framework\TestCase;

class ScannerTest extends TestCase
{
    public function testGetClassByDir()
    {
        $src = ['E:\air\annotation\src', 'E:\air\annotation\tests'];

        $scanner = new Scanner($src);
        $this->assertIsArray($scanner->getPaths());

        echo PHP_EOL;
        print_r($scanner->scanClasses());
    }
}
