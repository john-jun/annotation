<?php
declare(strict_types=1);
namespace Air\Annotation\Annotated;

use ReflectionClass;
use ReflectionProperty;

class AnnotatedProperty
{
    /** @var ReflectionProperty */
    private $property;

    /** @var mixed */
    private $annotation;

    /**
     * AnnotatedProperty constructor.
     * @param ReflectionProperty $property
     * @param $annotation
     */
    public function __construct(ReflectionProperty $property, $annotation)
    {
        $this->property = $property;
        $this->annotation = $annotation;
    }

    /**
     * @return ReflectionClass
     */
    public function getClass(): ReflectionClass
    {
        return $this->property->getDeclaringClass();
    }

    /**
     * @return ReflectionProperty
     */
    public function getProperty(): ReflectionProperty
    {
        return $this->property;
    }

    /**
     * @return mixed
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }
}
