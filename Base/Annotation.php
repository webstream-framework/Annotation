<?php
namespace WebStream\Annotation\Base;

use WebStream\Annotation\Container\AnnotationContainer;
use WebStream\Annotation\Container\ContainerFactory;
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
     * @param array<string> アノテーションリスト
     */
    public function __construct(array $annotations = [])
    {
        $this->logger = new class() { function __call($name, $args) {} };
        $this->onInject($annotations);
    }

    /**
     * Add injected log
     * @param object アノテーションクラスオブジェクト
     */
    // public function onInjectLog(Annotation $class)
    // {
    //     $this->logger->debug("@" . get_class($class) . " injected.");
    // }

    /**
     * Injected event
     * @param AnnotationContainer アノテーションコンテナ
     */
    abstract public function onInject(array $annotations);
}
