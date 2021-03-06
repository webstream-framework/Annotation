<?php

namespace WebStream\Annotation\Base;

use WebStream\DI\Injector;

/**
 * Annotaion
 * @author Ryuichi TANAKA.
 * @since 2014/05/11
 * @version 0.7
 */
abstract class Annotation
{
    use Injector;

    /**
     * @var Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * constructor
     * @param array $annotations
     */
    public function __construct(array $annotations = [])
    {
        $this->logger = new class () { public function __call($name, $args) {} };
        $this->onInject($annotations);
    }

    /**
     * Injected event
     * @param array $annotations
     */
    abstract public function onInject(array $annotations);
}
