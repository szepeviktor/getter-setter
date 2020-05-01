<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

require __DIR__ . '/MyCode.php';

new MyCode();
