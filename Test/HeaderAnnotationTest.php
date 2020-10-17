<?php

namespace WebStream\Annotation\Test;

require_once dirname(__FILE__) . '/../Modules/DI/Injector.php';
require_once dirname(__FILE__) . '/../Modules/Exception/ApplicationException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/SystemException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/AnnotationException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/InvalidArgumentException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/InvalidRequestException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Delegate/ExceptionDelegator.php';
require_once dirname(__FILE__) . '/../Modules/Container/Container.php';
require_once dirname(__FILE__) . '/../Base/Annotation.php';
require_once dirname(__FILE__) . '/../Base/IAnnotatable.php';
require_once dirname(__FILE__) . '/../Base/IMethod.php';
require_once dirname(__FILE__) . '/../Base/IRead.php';
require_once dirname(__FILE__) . '/../Reader/AnnotationReader.php';
require_once dirname(__FILE__) . '/../Attributes/Header.php';
require_once dirname(__FILE__) . '/../Test/Providers/HeaderAnnotationProvider.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/HeaderFixture1.php';

use PHPUnit\Framework\TestCase;
use WebStream\Annotation\Attributes\Header;
use WebStream\Annotation\Reader\AnnotationReader;
use WebStream\Annotation\Test\Providers\HeaderAnnotationProvider;
use WebStream\Container\Container;

/**
 * HeaderAnnotationTest
 * @author Ryuichi TANAKA.
 * @since 2017/01/09
 * @version 0.7
 */
class HeaderAnnotationTest extends TestCase
{
    use HeaderAnnotationProvider;

    /**
     * 正常系
     * @Headerの情報が取得できること
     * @test
     * @dataProvider okProvider
     * @param $clazz
     * @param $action
     * @param $requestMethod
     * @param $contentType
     * @throws \ReflectionException
     */
    public function okAnnotationTest($clazz, $action, $requestMethod, $contentType)
    {
        $instance = new $clazz();
        $container = new Container();
        $container->requestMethod = $requestMethod;
        $container->contentType = $contentType;
        $container->logger = new class ()
        {
            function __call($name, $args)
            {
            }
        };
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($action);
        $annotationReader->readable(Header::class, $container);
        $annotationReader->readMethod();

        $this->assertEquals(
            [Header::class => [
                ['contentType' => $contentType]
            ]],
            $annotationReader->getAnnotationInfoList()
        );
    }

    /**
     * 異常系
     * 実行時エラーが発生した場合、例外が発生すること
     * @test
     * @dataProvider runtimeErrorProvider
     * @param $clazz
     * @param $action
     * @param $requestMethod
     * @throws \Exception
     */
    public function ngRuntimeErrorTest($clazz, $action, $requestMethod)
    {
        $this->expectException(\WebStream\Exception\Extend\InvalidRequestException::class);
        $instance = new $clazz();
        $container = new Container();
        $container->requestMethod = $requestMethod;
        $container->contentType = "html";
        $container->logger = new class ()
        {
            function __call($name, $args)
            {
            }
        };
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($action);
        $annotationReader->readable(Header::class, $container);
        $annotationReader->readMethod();
        $exception = $annotationReader->getException();

        $this->assertNotNull($exception);
        $exception->raise();
    }

    /**
     * 異常系
     * 定義エラーがある場合、例外が発生すること
     * @test
     * @dataProvider annotationErrorProvider
     * @param $clazz
     * @param $action
     * @param $requestMethod
     * @throws \Exception
     */
    public function ngAnnotationErrorTest($clazz, $action, $requestMethod)
    {
        $this->expectException(\WebStream\Exception\Extend\AnnotationException::class);
        $instance = new $clazz();
        $container = new Container();
        $container->requestMethod = $requestMethod;
        $container->contentType = "html";
        $container->logger = new class ()
        {
            function __call($name, $args)
            {
            }
        };
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($action);
        $annotationReader->readable(Header::class, $container);
        $annotationReader->readMethod();
        $exception = $annotationReader->getException();

        $this->assertNotNull($exception);
        $exception->raise();
    }

    /**
     * 異常系
     * アノテーション読み込み設定をしていない場合、読み込み結果が取得できないこと
     * @test
     * @dataProvider okProvider
     * @param $clazz
     * @param $action
     * @param $requestMethod
     * @throws \ReflectionException
     */
    public function ngUnReadableAnnotationTest($clazz, $action, $requestMethod)
    {
        $instance = new $clazz();
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($action);
        $annotationReader->readMethod();
        $this->assertEmpty($annotationReader->getAnnotationInfoList());
    }
}
