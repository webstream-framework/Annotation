<?php

namespace WebStream\Annotation\Reader;

use Doctrine\Common\Annotations\AnnotationReader as DoctrineAnnotationReader;
use WebStream\Annotation\Base\IAnnotatable;
use WebStream\Annotation\Base\IClass;
use WebStream\Annotation\Base\IExtension;
use WebStream\Annotation\Base\IMethod;
use WebStream\Annotation\Base\IMethods;
use WebStream\Annotation\Base\IProperty;
use WebStream\Annotation\Base\IRead;
use WebStream\Annotation\Reader\Extend\ExtendReader;
use WebStream\Container\Container;
use WebStream\DI\Injector;
use WebStream\Exception\Delegate\ExceptionDelegator;
use WebStream\Exception\Extend\AnnotationException;

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
     * @var IAnnotatable インスタンス
     */
    private IAnnotatable $instance;

    /**
     * @var array<string> 読み込み可能アノテーション情報
     */
    private array $readableMap;

    /**
     * @var array<ExtendReader> 拡張アノテーションリーダー
     */
    private array $extendReaderMap;

    /**
     * @var array<string> アノテーション情報リスト
     */
    private array $annotationInfoList;

    /**
     * @var array<string> アノテーション情報リスト(拡張リーダー処理済み)
     */
    private array $annotationInfoExtendList;

    /**
     * @var ExceptionDelegator 読み込み時の例外
     */
    private ExceptionDelegator $exception;

    /**
     * @var string アクションメソッド
     */
    private string $actionMethod;

    /**
     * @var Container デフォルト依存コンテナ
     */
    private Container $defaultContainer;

    /**
     * constructor
     * @param IAnnotatable $instance
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
        $this->extendReaderMap = [];
        $this->annotationInfoList = [];
        $this->defaultContainer = new Container(false);
    }

    /**
     * アノテーション情報リストを返却する
     * @return array<mixed> アノテーション情報リスト
     * @throws \ReflectionException
     */
    public function getAnnotationInfoList(): array
    {
        if (empty($this->extendReaderMap)) {
            return $this->annotationInfoList;
        }

        foreach ($this->annotationInfoList as $key => $annotationInfo) {
            if (!array_key_exists($key, $this->extendReaderMap)) {
                continue;
            }
            $readerClasspath = $this->extendReaderMap[$key];
            $refClass = new \ReflectionClass($readerClasspath);
            $reader = $refClass->newInstance();
            $reader->read($annotationInfo);
            $this->annotationInfoList[$key] = $reader->getAnnotationInfo();
        }

        return $this->annotationInfoList;
    }

    /**
     * 発生した例外を返却する
     * @return ExceptionDelegator 発生した例外
     */
    public function getException(): ?ExceptionDelegator
    {
        return $this->exception ?? null;
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
     * @param Container|null $container
     */
    public function readable(string $classpath, Container $container = null)
    {
        $this->readableMap[$classpath] = $container;
    }

     /**
      * 拡張アノテーションリーダーを設定する
      * @param string アノテーションクラスパス
      * @param string 拡張アノテーションリーダークラスパス
      */
    public function useExtendReader(string $annotationClasspath, string $readerClasspath)
    {
        $this->extendReaderMap[$annotationClasspath] = $readerClasspath;
    }

    /**
     * アノテーション情報を読み込む
     */
    public function read()
    {
        try {
            $this->readClass();
            $this->readMethod();
            $this->readProperty();
        } catch (\Exception $e) {
            $this->initialize();
            throw new AnnotationException($e);
        }
    }

    /**
     * クラス情報を読み込む
     * @throws \ReflectionException
     */
    public function readClass()
    {
        $reader = new DoctrineAnnotationReader();
        $refClass = new \ReflectionClass($this->instance);

        while ($refClass !== false) {
            $annotations = $reader->getClassAnnotations($refClass);

            if (!empty($annotations)) {
                for ($i = 0, $count = count($annotations); $i < $count; $i++) {
                    $annotation = $annotations[$i];

                    if (!$annotation instanceof IClass) {
                        continue;
                    }

                    $key = get_class($annotation);
                    $container = null;
                    if (!array_key_exists($key, $this->readableMap)) {
                        if ($annotation instanceof IExtension) {
                            $container = $this->defaultContainer;
                        } else {
                            continue;
                        }
                    } else {
                        $container = $this->readableMap[$key];
                    }

                    try {
                        $annotation->onClassInject($this->instance, $refClass, $container);
                    } catch (\Exception $e) {
                        if (!isset($this->exception)) {
                            $this->exception = new ExceptionDelegator($this->instance, $e);
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

    /**
     * メソッド情報を読み込む
     */
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

                    // IMethodを実装している場合、アクションメソッドのアノテーション以外は読み込まない
                    // PHPのメソッドは大文字小文字を区別しないため、そのまま比較するとルーティング解決結果と実際のメソッド名が合わないケースがある
                    // PHPの仕様に合わせてメソッド名の文字列比較は小文字に変換してから行う
                    if ($annotation instanceof IMethod && strtolower($this->actionMethod) !== strtolower($refMethod->name)) {
                        continue;
                    }

                    // 読み込み可能なアノテーション以外は読み込まない
                    $key = get_class($annotation);
                    $container = null;
                    if (!array_key_exists($key, $this->readableMap)) {
                        if ($annotation instanceof IExtension) {
                            $container = $this->defaultContainer;
                        } else {
                            continue;
                        }
                    } else {
                        $container = $this->readableMap[$key];
                    }

                    try {
                        $annotation->onMethodInject($this->instance, $refMethod, $container);
                    } catch (\Exception $e) {
                        if (!isset($this->exception)) {
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

    /**
     * プロパティ情報を読み込む
     */
    private function readProperty()
    {
        $reader = new DoctrineAnnotationReader();
        $refClass = $this->refClass;

        while ($refClass !== false) {
            foreach ($refClass->getProperties() as $refProperty) {
                if ($refClass->getName() !== $refProperty->class) {
                    continue;
                }

                $annotations = $reader->getPropertyAnnotations($refProperty);

                // アノテーション定義がなければ次へ
                if (empty($annotations)) {
                    continue;
                }

                for ($i = 0, $count = count($annotations); $i < $count; $i++) {
                    $annotation = $annotations[$i];

                    if (!$annotation instanceof IProperty) {
                        continue;
                    }

                    $key = get_class($annotation);
                    $container = null;
                    if (!array_key_exists($key, $this->readableMap)) {
                        if ($annotation instanceof IExtension) {
                            $container = $this->defaultContainer;
                        } else {
                            continue;
                        }
                    } else {
                        $container = $this->readableMap[$key];
                    }

                    try {
                        $annotation->onPropertyInject($this->instance, $refProperty, $container);
                    } catch (\Exception $e) {
                        if (!isset($this->exception)) {
                            $this->exception = new ExceptionDelegator($this->instance, $e);
                        }
                        continue;
                    }

                    // IReadを実装している場合、任意のデータを返却する
                    if ($annotation instanceof IRead) {
                        if (!array_key_exists($key, $this->annotationInfoList)) {
                            $this->annotationInfoList[$key] = [];
                        }
                        $this->annotationInfoList[$key][] = $annotation->onInjected();
                    }
                }
            }

            $refClass = $refClass->getParentClass();
        }
    }
}
