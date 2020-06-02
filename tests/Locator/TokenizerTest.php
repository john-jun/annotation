<?php
namespace Air\Annotation\Test\Locator;

use Air\Annotation\Locator\Tokenizer;
use PHPUnit\Framework\TestCase;

class TokenizerTest extends TestCase
{
    public function testGetClassByCode()
    {
        $code = file_get_contents('E:\air\annotation\tests\Locator\TokenizerTest.php');
        $token = new Tokenizer();

        $this->assertIsObject($token->parseClass($code));
    }
}