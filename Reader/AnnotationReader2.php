<?php
namespace WebStream\Annotation\Reader;

use WebStream\Annotation\Base\IAnnotatable;
use WebStream\Annotation\Base\Annotation;
use WebStream\Annotation\Base\IClass;
use WebStream\Annotation\Base\IMethod;
use WebStream\Annotation\Base\IMethods;
use WebStream\Annotation\Base\IProperty;
use WebStream\Annotation\Base\IRead;
use WebStream\Container\Container;
use WebStream\DI\Injector;
use WebStream\Exception\Delegate\ExceptionDelegator;
use WebStream\Exception\Extend\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader as DoctrineAnnotationReader;
use Doctrine\Common\Annotations\AnnotationException as DoctrineAnnotationException;

/**
 * AnnotationReader
 * @author Ryuichi TANAKA.
 * @since 2014/05/10
 * @version 0.4
 */
class AnnotationReader
{
    use Injector;

    /**
     * @var \ReflectionClass リフレクションクラスオブジェクト
     */
    // private $refClass;

    /**
     * @var IAnnotatable インスタンス
     */
    private $instance;

    /**
     * @var Logger ロガー
     */
    // private $logger;

    /**
     * @var Container コンテナ
     */
    // private $container;

    /**
     * @var array<string> 読み込み可能アノテーション情報
     */
    private $readableMap;

    /**
     * @var array<Annotation> アノテーション情報リスト
     */
    private $annotationInfoList;

    /**
     * @var callable 読み込み時の例外
     */
    private $exception;

    /**
     * @var string 読み込み対象アノテーションクラスパス
     */
    private $annotationClasspath;

    /**
     * @var string アクションメソッド
     */
    private $actionMethod;

    /**
     * constructor
     * @param IAnnotatable ターゲットインスタンス
     * @param Container 依存コンテナ
     */
    public function __construct(IAnnotatable $instance)
    {
        $this->initialize();
        $this->instance = $instance;
    }

    /**
     * 初期化処理
     */
    private function initialize()
    {
        $this->readableMap = [];
        $this->annotationInfoList = [];
    }

    /**
     * アノテーション情報リストを返却する
     * @param array<Annotation> アノテーション情報リスト
     */
    public function getAnnotationInfoList(): array
    {
        return $this->annotationInfoList;
    }

    /**
     * 発生した例外を返却する
     * @param ExceptionDelegator 発生した例外
     */
    public function getException(): ExceptionDelegator
    {
        return $this->exception;
    }

    /**
     * アクションメソッドを設定する
     * @param string アクションメソッド
     */
    public function setActionMethod(string $actionMethod)
    {
        $this->actionMethod = $actionMethod;
    }

    /**
     * 読み込み可能アノテーション情報を設定する
     * @param string アノテーションクラスパス
     * @param Container アノテーションクラス依存コンテナ
     */
    public function readable(string $classpath, Container $container = null)
    {
        $this->readableMap[$classpath] = $container;
    }

    public function read()
    {
        try {
            $this->readClass();
            $this->readMethod();
            $this->readProperty();
        } catch (DoctrineAnnotationException $e) {
            $this->initialize();
            throw new AnnotationException($e);
        }

    }

    private function readClass()
    {
        $reader = new DoctrineAnnotationReader();
        $refClass = new \ReflectionClass($this->instance);

        while ($refClass !== false) {
            $annotations = $reader->getClassAnnotations($refClass);
            var_dump($annotations);

            // アノテーション定義がなければ次へ
            if (!empty($annotations)) {
                for ($i = 0, $count = count($annotations); $i < $count; $i++) {
                    $annotation = $annotations[$i];

                    var_dump($annotation);

                    // $annotation->inject('logger', $this->container->logger);
                    //
                    // if (!$annotation instanceof IClass) {
                    //     continue;
                    // }
                    //
                    // // アノテーションクラスパスが指定された場合、一致したアノテーション以外は読み込まない
                    // if ($this->annotationClasspath !== null && $this->annotationClasspath !== get_class($annotation)) {
                    //     continue;
                    // }
                    //
                    // try {
                    //     $annotation->onClassInject($this->instance, $this->container, $refClass);
                    // } catch (\Exception $e) {
                    //     if ($this->exception === null) {
                    //         $this->exception = new ExceptionDelegator($this->instance, $e);
                    //     }
                    //     continue;
                    // }
                    //
                    // $key = get_class($annotation);
                    //
                    // // IReadを実装している場合、任意のデータを返却する
                    // if ($annotation instanceof IRead) {
                    //     if (!array_key_exists($key, $this->injectedAnnotations)) {
                    //         $this->injectedAnnotations[$key] = [];
                    //     }
                    //     $this->injectedAnnotations[$key][] = $annotation->onInjected();
                    // }
                }
            }

            $refClass = $refClass->getParentClass();
        }
    }

    public function readMethod()
    {
        $reader = new DoctrineAnnotationReader();
        $refClass = new \ReflectionClass($this->instance);

        while ($refClass !== false) {
            foreach ($refClass->getMethods() as $refMethod) {
                if ($refClass->getName() !== $refMethod->class) {
                    continue;
                }

                $annotations = $reader->getMethodAnnotations($refMethod);
                if (empty($annotations)) {
                    continue;
                }

                for ($i = 0, $count = count($annotations); $i < $count; $i++) {
                    $annotation = $annotations[$i];

                    if (!$annotation instanceof IMethod && !$annotation instanceof IMethods) {
                        continue;
                    }

                    $key = get_class($annotation);
                    $container = array_key_exists($key, $this->readableMap) ? $this->readableMap[$key] : null;

                    if ($container === null) {
                        continue;
                    }

                    try {
                        $annotation->onMethodInject($this->instance, $refMethod, $container);
                    } catch (\Exception $e) {
                        if ($this->exception === null) {
                            $this->exception = new ExceptionDelegator($this->instance, $e, $this->actionMethod);
                        }
                        continue;
                    }

                    // IReadを実装している場合、任意のデータを返却する
                    if ($annotation instanceof IRead) {
                        if (!array_key_exists($key, $this->annotationInfoList)) {
                            $this->annotationInfoList[$key] = [];
                        }
                        $this->annotationInfoList[$key][] = $annotation->getAnnotationInfo();
                    }
                }
            }

            $refClass = $refClass->getParentClass();
        }
    }

    private function readProperty()
    {

    }

}
