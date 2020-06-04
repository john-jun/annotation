<?php
declare(strict_types=1);
namespace Air\Annotation;

use Air\Annotation\Exception\LocatorException;
use Air\Annotation\Scanner\ClassName;
use Air\Annotation\Scanner\Scanner;
use Doctrine\Common\Annotations\AnnotationReader;
use Exception;
use Generator;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use Throwable;

/**
 * Class AnnotationLocator
 * @package Air\Annotation
 */
final class AnnotationScanner
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Scanner
     */
    private $scanner;

    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var array
     */
    private $annotatedEntities;

    /**
     * AnnotationScanner constructor.
     * @param array $paths
     * @param AnnotationReader|null $reader
     * @param LoggerInterface|null $logger
     */
    public function __construct(array $paths = [], AnnotationReader $reader = null, LoggerInterface $logger = null)
    {
        $this->logger = $logger;
        $this->scanner = new Scanner();
        $this->annotationReader = $reader ?? new AnnotationReader();

        if (count($paths) > 0) {
            $this->scan($paths);
        }
    }

    /**
     * @param array $paths
     */
    public function scan(array $paths)
    {
        /** @var $class ClassName **/
        foreach ($this->scanner->scanClassName($paths) as $class) {
            try {
                $reflection = $this->classReflection($class->getClassName());
            } catch (LocatorException $e) {
                continue;
            }

            //parse class annotations
            foreach ($this->annotationReader->getClassAnnotations($reflection) as $annotation) {
                $key = md5(get_class($annotation).$reflection->getName());
                $this->annotatedEntities[$key] = new AnnotationEntity($reflection, $annotation);
            }

            //parse properties annotations
            foreach ($reflection->getProperties() as $property) {
                foreach ($this->annotationReader->getPropertyAnnotations($property) as $annotation) {
                    $key = md5(get_class($annotation).$reflection->getName().'P'.$property->getName());
                    $this->annotatedEntities[$key] = new AnnotationEntity($property, $annotation);
                }
            }

            //parse method annotations
            foreach ($reflection->getMethods() as $method) {
                foreach ($this->annotationReader->getMethodAnnotations($method) as $annotation) {
                    $key = md5(get_class($annotation).$reflection->getName().'M'.$method->getName());
                    $this->annotatedEntities[$key] = new AnnotationEntity($method, $annotation);
                }
            }
        }

        //report scanner error
        $this->reportScanException();
    }

    /**
     * @param string $annotation
     * @return Generator
     */
    public function getUseAnnotationClasses(string $annotation)
    {
        return $this->getTargeted($annotation);
    }

    /**
     * @return array
     */
    public function getAnnotatedEntities(): array
    {
        return $this->annotatedEntities ?? [];
    }

    /**
     * @param string $annotation
     * @return Generator
     */
    private function getTargeted(string $annotation)
    {
        try {
            $annotation = $this->classReflection($annotation);
            $annotationClassName = $annotation->getName();

            /** @var $target AnnotationEntity **/
            foreach ($this->getAnnotatedEntities() as $target) {
                if ($annotation->isTrait()) {
                    $classUsedTraits = $this->getClassUsedTraits(get_class($target->getAnnotation()));
                    if (isset($classUsedTraits[$annotationClassName])) {
                        yield $target;
                    }
                }

                if (!$annotation->isTrait() && !$target->getClass()->isTrait() && $target->getAnnotation() instanceof $annotationClassName) {
                    yield $target;
                }
            }
        } catch (Exception $e) {
            $this->logger->error($e, ['targeted' => static::class]);
        }
    }

    /**
     * @param string $class
     * @return array
     */
    private function getClassUsedTraits(string $class): array
    {
        $traits = [];

        while (false !== $class) {
            $traits = array_merge(class_uses($class), $traits);
            $class = get_parent_class($class);
        }

        foreach (array_flip($traits) as $trait) {
            $traits = array_merge(class_uses($trait), $traits);
        }

        return array_unique($traits);
    }

    /**
     * @param string $class
     * @return ReflectionClass
     * @throws LocatorException
     */
    private function classReflection(string $class)
    {
        spl_autoload_register($loader = function ($class) {
            if ($class === LocatorException::class) {
                return;
            }

            throw new LocatorException("Class '{$class}' can not be loaded");
        });

        try {
            return new ReflectionClass($class);
        } catch (Throwable $e) {
            if ($e instanceof LocatorException && $e->getPrevious() !== null) {
                $e = $e->getPrevious();
            }

            if ($this->logger) {
                $this->logger->error(
                    sprintf("%s: %s in %s:%s", $class, $e->getMessage(), $e->getFile(), $e->getLine()),
                    ['error' => $e]
                );
            }

            throw new LocatorException($e->getMessage(), $e->getCode(), $e);
        } finally {
            spl_autoload_unregister($loader);
        }
    }

    private function reportScanException()
    {
        if ($this->logger) {
            foreach ($this->scanner->getExceptions() as $exception) {
                $this->logger->error($exception, ['scan_class_file' => static::class]);
            }
        }
    }
}
