<?php
namespace WebStream\Annotation;

use WebStream\Annotation\Base\Annotation;
use WebStream\Annotation\Base\IAnnotatable;
use WebStream\Annotation\Base\IRead;
use WebStream\Annotation\Base\IMethods;
use WebStream\Annotation\Container\AnnotationContainer;
use WebStream\Annotation\Utility\AnnotationUtils;
use WebStream\Container\Container;
use WebStream\Exception\Extend\AnnotationException;

/**
 * Filter
 * @author Ryuichi TANAKA.
 * @since 2013/10/20
 * @version 0.4
 *
 * @Annotation
 * @Target("METHOD")
 */
class Filter extends Annotation implements IMethods, IRead
{
    use AnnotationUtils;

    /**
     * @var WebStream\Annotation\Container\AnnotationContainer アノテーションコンテナ
     */
    private $annotation;

    /**
     * @var WebStream\Annotation\Container\AnnotationContainer 注入結果
     */
    private $injectedContainer;

    /**
     * {@inheritdoc}
     */
    public function onInject(AnnotationContainer $annotation)
    {
        $this->annotation = $annotation;
        $this->injectedContainer = new AnnotationContainer();
    }

    /**
     * {@inheritdoc}
     */
    public function onInjected()
    {
        return $this->injectedContainer;
    }

    /**
     * {@inheritdoc}
     */
    public function onMethodInject(IAnnotatable $instance, \ReflectionMethod $method, Container $container)
    {
        $annotation = $this->annotation;
        $classpath = get_class($instance);
        // $action = $container->router->action;
        // $this->injectedContainer->method = $method;
        // $this->injectedContainer->classpath = get_class($instance);
        // $this->injectedContainer->action = $container->router->action;

        // TODO 読み込んだ時点でActionから使うかどうか不明なので遅延実行すること
        //
        //

        $this->injectedContainer = function () {
            $exceptMethods = [];
            // アクションメソッドの@Filter(type="skip")をチェックする
            // 1メソッドに対して複数の@Filterが指定されてもエラーにはしない
            if ($classpath . "#" . $action === $method->class . "#" . $method->name) {
                if ($annotation->type === 'skip') {
                    $exceptMethods = $annotation->except;
                    if (!is_array($exceptMethods)) {
                        $exceptMethods = [$exceptMethods];
                    }
                }
            }

            $type = $annotation->type;
            $only = $annotation->only;
            $except = $annotation->except;
            $isInitialized = false;

            // initializeは複数回指定されたら例外
            if ($type === "initialize") {
                if ($isInitialized) {
                    throw new AnnotationException("Can not multiple define @Filter(type=\"initialize\") at method.");
                }
                $isInitialized = true;
            } elseif ($this->inArray($type, ["before", "after"])) {
                // skip filterが有効なら適用しない
                // クラスに関係なくメソッド名が一致した場合すべて適用しない
                if ($this->inArray($method->name, $exceptMethods)) {
                    return;
                }
                // only
                if ($only !== null) {
                    $onlyList = $only;
                    if (!is_array($onlyList)) {
                        $onlyList = [$onlyList];
                    }
                    // アクションメソッド名がonlyListに含まれていれば実行対象とする
                    if (!$this->inArray($action, $onlyList)) {
                        return;
                    }
                }
                // exceptは親クラス以上すべてのメソッドに対して適用するのでメソッド名のみ取得
                if ($except !== null) {
                    $exceptList = $except;
                    if (!is_array($exceptList)) {
                        $exceptList = [$exceptList];
                    }
                    // アクションメソッド名がexceptListに含まれていれば実行対象としない
                    if ($this->inArray($action, $exceptList)) {
                        return;
                    }
                }
            } else {
                return;
            }


        };




    }
}
