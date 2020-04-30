<?php

declare(strict_types=1);

namespace SzepeViktor\GettersSetters\Annotations;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class Setter
{
    /** @var string */
    public $visibility;
}
