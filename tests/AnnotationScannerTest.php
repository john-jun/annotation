<?php
namespace Air\Annotation\Test;

use Air\Annotation\AnnotationScanner;
use Air\Annotation\AnnotationTrait;
use Air\Annotation\Test\Annotation\Cache\ApcuTrait;
use Air\Annotation\Test\Annotation\Route;
use PHPUnit\Framework\TestCase;

class AnnotationScannerTest extends TestCase
{
    public function testAnnotation()
    {
        $annotation = new AnnotationScanner(['E:\air\annotation\tests']);
        $this->assertIsArray($annotation->getAnnotatedEntities());

        print_r($annotation->getAnnotatedEntities());

        foreach ($annotation->findClasses(AnnotationTrait::class) as $class) {
            echo PHP_EOL;
            var_dump($class);
        }
    }
}
