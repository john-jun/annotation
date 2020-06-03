<?php
declare(strict_types=1);
namespace Air\Annotation;

/**
 * Trait AnnotationTrait
 * @package Air\Annotation
 */
trait AnnotationTrait
{
    /**
     * AnnotationTrait constructor.
     * @param null $value
     */
    public function __construct($value = null)
    {
        if (is_array($value)) {
            foreach ($value as $key => $val) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $val;
                }
            }
        }
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        return null;
    }
}
