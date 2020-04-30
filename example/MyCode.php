<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationRegistry;
use Foo\Annotations\Getter;
use Foo\Annotations\Setter;
use Foo\GetterSetter;

AnnotationRegistry::registerLoader('class_exists');

class MyCode
{
    use GetterSetter;

    /**
     * @Getter(visibility="public")
     * @Setter(visibility="protected")
     *
     * @var string
     */
    private $bar;

    public function __construct()
    {
        $this->bar = 'default Value :)';
        var_dump( $this->getBar() );
        $this->setBar('new Value.');
        var_dump( $this->getBar() );
    }
}

new MyCode();
