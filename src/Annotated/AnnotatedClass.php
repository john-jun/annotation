<?php
declare(strict_types=1);
namespace Air\Annotation\Annotated;

use ReflectionClass;

/**
 * Class AnnotatedClass
 * @package Air\Annotation
 */
final class AnnotatedClass
{
    /** @var ReflectionClass */
    private $class;

    /** @var mixed */
    private $annotation;

    /**
     * AnnotatedClass constructor.
     * @param ReflectionClass $class
     * @param $annotation
     */
    public function __construct(ReflectionClass $class, $annotation)
    {
        $this->class = $class;
        $this->annotation = $annotation;
    }

    /**
     * @return ReflectionClass
     */
    public function getClass(): ReflectionClass
    {
        return $this->class;
    }

    /**
     * @return mixed
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }
}
