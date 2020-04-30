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

        $reflectionClass = new ReflectionClass(get_class($this));
        $propertyReflection = $reflectionClass->getProperty($property);
        $reader = new AnnotationReader();

        switch ($method) {
            case 'get':
                $getterAnnotation = $reader->getPropertyAnnotation($propertyReflection, Getter::class);
                if (! $getterAnnotation instanceof Getter) {
                    throw new \Exception('Method does not exist');
                }
                if (property_exists($getterAnnotation, 'visibility')) {
                    $visibility = $getterAnnotation->visibility;
                }
                // TODO Implement visibility constrains

                return $this->{$property};
            case 'set':
                $setterAnnotation = $reader->getPropertyAnnotation($propertyReflection, Setter::class);
                if (! $setterAnnotation instanceof Setter) {
                    throw new \Exception('Method does not exist');
                }
                if (property_exists($setterAnnotation, 'visibility')) {
                    $visibility = $setterAnnotation->visibility;
                }
                // TODO Implement visibility constrains

                if (! array_key_exists(0, $arguments)) {
                    throw new \Exception('Argument is missing for setter');
                }

                $this->{$property} = $arguments[0];

                return $this;
        }
    }
}
