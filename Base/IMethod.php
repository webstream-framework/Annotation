<?php

namespace WebStream\Annotation\Base;

use WebStream\Container\Container;

/**
 * IMethod
 * @author Ryuichi TANAKA.
 * @since 2015/02/10
 * @version 0.4
 */
interface IMethod
{
    /**
     * メソッドオブジェクトを注入
     * @param IAnnotatable 注入先インスタンス
     * @param \ReflectionMethod リフレクションメソッドオブジェクト
     * @param Container $container
     */
    public function onMethodInject(IAnnotatable $instance, \ReflectionMethod $method, Container $container);
}
