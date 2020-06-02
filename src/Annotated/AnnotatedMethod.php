<?php
declare(strict_types=1);
namespace Air\Annotation\Annotated;

use ReflectionClass;
use ReflectionMethod;

final class AnnotatedMethod
{
    /** @var ReflectionMethod */
    private $method;

    /** @var mixed */
    private $annotation;

    /**
     * @param ReflectionMethod $method
     * @param mixed             $annotation
     */
    public function __construct(ReflectionMethod $method, $annotation)
    {
        $this->method = $method;
        $this->annotation = $annotation;
    }

    /**
     * @return ReflectionClass
     */
    public function getClass(): ReflectionClass
    {
        return $this->method->getDeclaringClass();
    }

    /**
     * @return ReflectionMethod
     */
    public function getMethod(): ReflectionMethod
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }
}
