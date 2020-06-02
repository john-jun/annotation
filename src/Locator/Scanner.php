<?php
declare(strict_types=1);
namespace Air\Annotation\Locator;

use Air\Annotation\Exception\LocatorException;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Finder\Finder;
use Throwable;

/**
 * Class Scanner
 * @package Air\Annotation
 */
final class Scanner
{
    /**
     * @var array
     */
    private $paths;

    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * Scanner constructor.
     * @param array $paths
     * @param LoggerInterface $logger
     */
    public function __construct(array $paths, LoggerInterface $logger = null)
    {
        $this->paths = $paths;
        $this->tokenizer = new Tokenizer();

        $this->logger = $logger;
        $this->finder = (new Finder())->files()->in($paths)->name('*.php');
    }

    /**
     * @param null $target
     * @return array
     * @throws ReflectionException
     */
    public function scanClasses($target = null): array
    {
        if (!empty($target) && (is_object($target) || is_string($target))) {
            $target = new ReflectionClass($target);
        }

        $parseClasses = [];
        foreach ($this->finder->files()->getIterator() as $file) {
            $parseClass = $this->tokenizer->parseClass($file->getContents());
            if (is_null($parseClass)) {
                continue;
            }

            try {
                $reflection = $this->classReflection($parseClass->getClassName());
            } catch (LocatorException $e) {
                continue;
            }

            if (!$this->isTargeted($reflection, $target) || $reflection->isInterface()) {
                continue;
            }

            $parseClasses[$reflection->getName()] = $reflection;
        }

        return $parseClasses;
    }

    /**
     * @return array
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * @param ReflectionClass $class
     * @param ReflectionClass|null $target
     * @return bool
     */
    private function isTargeted(ReflectionClass $class, ReflectionClass $target = null): bool
    {
        if (empty($target)) {
            return true;
        }

        if (!$target->isTrait()) {
            return $class->isSubclassOf($target) || $class->getName() === $target->getName();
        }

        return in_array($target->getName(), $this->fetchTraits($class->getName()));
    }

    /**
     * @param string $class
     * @return array
     */
    private function fetchTraits(string $class): array
    {
        $traits = [];

        while ($class) {
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
}
