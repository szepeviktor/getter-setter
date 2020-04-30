<?php

declare(strict_types=1);

namespace Foo;

use Foo\Annotations\Getter;
use Foo\Annotations\Setter;
use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionClass;

trait GetterSetter
{
    /**
     * @link https://www.doctrine-project.org/projects/doctrine-annotations/en/latest/index.html
     *
     * @param array<int, mixed> $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        $method = substr($name, 0, 3);
        $property = lcfirst(substr($name, 3));
        $visibility = 'public';

        if (! in_array($method, ['get', 'set'], true)) {
            throw new \Exception('Method does not exist');
        }

        if (! property_exists($this, $property)) {
            throw new \Exception('Property does not exist');
        }

        $callers = debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        $reflectionClass = new ReflectionClass(get_class($this));
        $propertyReflection = $reflectionClass->getProperty($property);
        $reader = new AnnotationReader();

        switch ($method) {
            case 'get':
                $getterAnnotation = $reader->getPropertyAnnotation($propertyReflection, Getter::class);
                if (! $getterAnnotation instanceof Getter) {
                    throw new \BadMethodCallException('Method does not exist');
                }
                if (property_exists($getterAnnotation, 'visibility')) {
                    $visibility = $getterAnnotation->visibility;
                }
                if (! $this->checkVisibility($callers, $visibility)) {
                    throw new \BadMethodCallException('Method does not exist');
                }

                return $this->{$property};
            case 'set':
                $setterAnnotation = $reader->getPropertyAnnotation($propertyReflection, Setter::class);
                if (! $setterAnnotation instanceof Setter) {
                    throw new \Exception('Method does not exist');
                }
                if (property_exists($setterAnnotation, 'visibility')) {
                    $visibility = $setterAnnotation->visibility;
                }
                if (! $this->checkVisibility($callers, $visibility)) {
                    throw new \BadMethodCallException('Method does not exist');
                }
                if (! array_key_exists(0, $arguments)) {
                    throw new \Exception('Argument is missing for setter');
                }

                $this->{$property} = $arguments[0];

                return $this;
        }
    }

    /**
     * @param array<int, array> $callers
     */
    protected function checkVisibility(array $callers, string $visibility): bool
    {
        $calledFromObject = array_key_exists(1, $callers) && array_key_exists('object', $callers[1]);
        $thisClass = get_class($this);

        switch ($visibility) {
            case 'private':
                return $calledFromObject && $callers[1]['class'] === $thisClass;
            case 'protected':
                return $calledFromObject && ($callers[1]['class'] === $thisClass || is_subclass_of($callers[1]['object'], $thisClass));
            case 'public':
            default:
                return true;
        }
    }
}
