<?php
namespace WebStream\Annotation;

use WebStream\Annotation\Base\Annotation;
use WebStream\Annotation\Base\IAnnotatable;
use WebStream\Annotation\Base\IRead;
use WebStream\Annotation\Base\IClass;
use WebStream\Container\Container;
use WebStream\Exception\Extend\DatabaseException;

/**
 * Database
 * @author Ryuichi TANAKA.
 * @since 2013/12/07
 * @version 0.7
 *
 * @Annotation
 * @Target("CLASS")
 */
class Database extends Annotation implements IClass, IRead
{
    /**
     * @var array<string> 注入アノテーション情報
     */
    private $injectAnnotation;

    /**
     * @var array<string> 読み込みアノテーション情報
     */
    private $readAnnotation;

    /**
     * {@inheritdoc}
     */
    public function onInject(array $injectAnnotation)
    {
        $this->injectAnnotation = $injectAnnotation;
        $this->readAnnotation = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAnnotationInfo(): array
    {
        return $this->readAnnotation;
    }

    /**
     * {@inheritdoc}
     */
    public function onClassInject(IAnnotatable $instance, \ReflectionClass $class, Container $container)
    {
        $driver = $this->injectAnnotation['driver'];
        $config = $this->injectAnnotation['config'];

        if (!class_exists($driver)) {
            throw new DatabaseException("Database driver is undefined：" . $driver);
        }

        $configPath = $container->applicationInfo->applicationRoot . "/" . $config;
        $configRealPath = realpath($configPath);
        if (!file_exists($configRealPath)) {
            throw new DatabaseException("Database config file is not found: " . $configPath);
        }

        $this->injectedContainer->filepath = $class->getFileName();
        $this->injectedContainer->configPath = $configRealPath;
        $this->injectedContainer->driverClassPath = $driver;
    }
}
