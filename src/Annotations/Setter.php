<?php

declare(strict_types=1);

namespace Foo\Annotations;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class Setter
{
    public $visibility;
}