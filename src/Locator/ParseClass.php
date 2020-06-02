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
     * @var bool
     */
    private $abstract;

    /**
     * ParseClass constructor.
     * @param string $class
     * @param string $type
     * @param bool $abstract
     */
    public function __construct(string $class, string $type, bool $abstract = false)
    {
        $this->type = $type;
        $this->class = $class;
        $this->abstract = $abstract;
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
        return $this->abstract;
    }

    /**
     * @return bool
     */
    public function isInterface(): bool
    {
        return $this->getClassType() === 'interface';
    }
}
