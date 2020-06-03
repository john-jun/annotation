<?php
namespace Air\Annotation\Test\Scanner;

use Air\Annotation\Scanner\Tokenizer;
use PHPUnit\Framework\TestCase;

class TokenizerTest extends TestCase
{
    public function testGetClassByCode()
    {
        $code = file_get_contents('E:\air\annotation\tests\Scanner\TokenizerTest.php');
        $token = new Tokenizer();

        $this->assertIsObject($token->parseClass($code));
    }
}