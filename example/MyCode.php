<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationRegistry;
use SzepeViktor\GettersSetters\Annotations\Getter;
use SzepeViktor\GettersSetters\Annotations\Setter;
use SzepeViktor\GettersSetters\GetterSetter;

AnnotationRegistry::registerLoader('class_exists');

/**
 * @method string getBar()
 * @method self setBar(string $value)
 */
class MyCode
{
    use GetterSetter;

    /**
     * @Getter(visibility="private")
     * @Setter(visibility="public")
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
