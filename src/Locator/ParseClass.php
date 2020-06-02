<?php
declare(strict_types=1);
namespace Air\Annotation\Locator;

/**
 * Interface ParseClassInterface
 * @package Air\Annotation
 */
final class ParseClass
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $class;

    /**
     * ParseClass constructor.
     * @param string $class
     * @param string $type
     */
    public function __construct(string $class, string $type)
    {
        $this->type = $type;
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getClassType(): string
    {
        return $this->type;
    }

    public function isClass(): bool
    {
        return $this->getClassType() === 'class';
    }

    /**
     * @return bool
     */
    public function isTrait(): bool
    {
        return $this->getClassType() === 'trait';
    }

    /**
     * @return bool
     */
    public function isAbstract(): bool
    {
        return $this->getClassType() === 'abstract';
    }

    /**
     * @return bool
     */
    public function isInterface(): bool
    {
        return $this->getClassType() === 'interface';
    }
}
