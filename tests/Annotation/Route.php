<?php
namespace Air\Annotation\Test\Annotation;

use Air\Annotation\AnnotationTrait;
use Air\Annotation\Test\Annotation\Cache\ApcuTrait;

/**
 * @Annotation
 * Class Route
 * @package Air\Annotation\Test\Annotation
 */
class Route
{
    use AnnotationTrait;

    protected $id;
}
