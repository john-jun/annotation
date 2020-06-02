<?php
declare(strict_types=1);
namespace Air\Annotation\Test;

/**
 * @Annotation
 */
class RouteMapping
{
    private $id;

    public function __construct(array $value)
    {
        $this->id = $value['id'] ?? '';
    }
}
