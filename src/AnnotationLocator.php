<?php
declare(strict_types=1);
namespace Air\Annotation;

use Air\Annotation\Annotated\AnnotatedClass;
use Air\Annotation\Annotated\AnnotatedMethod;
use Air\Annotation\Annotated\AnnotatedProperty;
use Air\Annotation\Locator\Scanner;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionException;

/**
 * Class AnnotationLocator
 * @package Air\Annotation
 */
final class AnnotationLocator
{
    /** @var Scanner */
    private $scanner;

    /** @var AnnotationReader */
    private $reader;

    /** @var array */
    private $targets = [];

    /**
     * AnnotationLocator constructor.
     * @param Scanner $scanner
     * @param AnnotationReader|null $reader
     */
    public function __construct(Scanner $scanner, AnnotationReader $reader = null)
    {
        $this->scanner = $scanner;
        $this->reader = $reader ?? new AnnotationReader();
    }

    /**
     * @param array $targets
     * @return $this
     */
    public function withTargets(array $targets): self
    {
        $locator = clone $this;
        $locator->targets = $targets;

        return $locator;
    }

    /**
     * @param string $annotation
     * @return iterable
     * @throws ReflectionException
     */
    public function findClasses(string $annotation): iterable
    {
        foreach ($this->getTargets() as $target) {
            $found = $this->reader->getClassAnnotation($target, $annotation);

            if ($found !== null) {
                yield new AnnotatedClass($target, $found);
            }
        }
    }

    /**
     * @param string $annotation
     * @return iterable
     * @throws ReflectionException
     */
    public function findMethods(string $annotation): iterable
    {
        foreach ($this->getTargets() as $target) {
            foreach ($target->getMethods() as $method) {
                $found = $this->reader->getMethodAnnotation($method, $annotation);

                if ($found !== null) {
                    yield new AnnotatedMethod($method, $found);
                }
            }
        }
    }

    /**
     * @param string $annotation
     * @return iterable
     * @throws ReflectionException
     */
    public function findProperties(string $annotation): iterable
    {
        foreach ($this->getTargets() as $target) {
            foreach ($target->getProperties() as $property) {
                $found = $this->reader->getPropertyAnnotation($property, $annotation);

                if ($found !== null) {
                    yield new AnnotatedProperty($property, $found);
                }
            }
        }
    }

    /**
     * @return iterable
     * @throws ReflectionException
     */
    private function getTargets(): iterable
    {
        if ($this->targets === []) {
            yield from $this->scanner->scanClasses();
            return;
        }

        foreach ($this->targets as $target) {
            yield from $this->scanner->scanClasses($target);
        }
    }
}
