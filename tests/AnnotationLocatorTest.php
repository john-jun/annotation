<?php
namespace Air\Annotation\Test;

use Air\Annotation\AnnotationLocator;
use Air\Annotation\Locator\Scanner;
use PHPUnit\Framework\TestCase;

class AnnotationLocatorTest extends TestCase
{
    public function testAnnotation()
    {
        $Annotation = new AnnotationLocator(new Scanner(['E:\air\annotation\tests\Route']));


        foreach ($Annotation->findClasses(Route::class) as $class) {
            var_dump($class->getClass());
            var_dump($class->getAnnotation());
        }
    }
}
