<?php
declare(strict_types=1);
namespace Air\Annotation\Scanner;

use Symfony\Component\Finder\Finder;
use Throwable;

/**
 * Class Scanner
 * @package Air\Annotation
 */
final class Scanner
{
    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * @var array
     */
    private $exceptions;

    /**
     * Scanner constructor.
     */
    public function __construct()
    {
        $this->tokenizer = new Tokenizer();
    }

    /**
     * @param array $paths
     * @return array|null
     */
    public function scanClassName(array $paths): ?array
    {
        $finder = (new Finder())->files()->in($paths)->name('*.php');

        $classes = [];
        foreach ($finder as $file) {
            try {
                $className = $this->tokenizer->parseClass($file->getContents());
                if (is_null($className)) {
                    continue;
                }
            } catch (Throwable $e) {
                $this->exceptions[] = $e;

                continue;
            }

            $classes[] = $className;
        }

        return $classes;
    }

    /**
     * @return array
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }
}
