<?php

namespace WebStream\Annotation\Base;

use WebStream\Container\Container;

/**
 * IProperty
 * @author Ryuichi TANAKA.
 * @since 2015/02/10
 * @version 0.4
 */
interface IProperty
{
    /**
     * プロパティオブジェクトを注入
     * @param IAnnotatable 注入先インスタンス
     * @param \ReflectionProperty リフレクションプロパティオブジェクト
     * @param Container $container
     */
    public function onPropertyInject(IAnnotatable $instance, \ReflectionProperty $property, Container $container);
}
