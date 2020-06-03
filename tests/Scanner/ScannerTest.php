<?php
namespace Air\Annotation\Test\Scanner;

use Air\Annotation\Scanner\Scanner;
use PHPUnit\Framework\TestCase;

class ScannerTest extends TestCase
{
    public function testGetClassByDir()
    {
        $scanner = new Scanner();

        try {
            $this->assertIsArray($class = $scanner->scanClassName(['E:\air\annotation\src']));
        } catch (\Exception $e) {
           $this->assertInstanceOf(\Exception::class, $e);
        }
    }
}
