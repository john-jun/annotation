<?php
declare(strict_types=1);
namespace Air\Annotation;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Class AnnotationMapping
 * @package Air\Annotation
 */
final class AnnotationEntity
{
    /**
     * @var mixed
     */
    private $reflector;

    /**
     * @var mixed
     */
    private $annotation;

    /**
     * AnnotationMapping constructor.
     * @param $reflector
     * @param $annotation
     */
    public function __construct($reflector, $annotation)
    {
        $this->reflector = $reflector;
        $this->annotation = $annotation;
    }

    /**
     * @return ReflectionClass
     */
    public function getClass(): ReflectionClass
    {
        if ($this->reflector instanceof ReflectionClass) {
            return $this->reflector;
        }

        return $this->reflector->getDeclaringClass();
    }

    /**
     * @return ReflectionMethod|null
     */
    public function getMethod(): ?ReflectionMethod
    {
        if ($this->reflector instanceof ReflectionMethod) {
            return $this->reflector;
        }

        return null;
    }

    /**
     * @return ReflectionProperty|null
     */
    public function getProperty(): ?ReflectionProperty
    {
        if ($this->reflector instanceof ReflectionProperty) {
            return $this->reflector;
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }
}
