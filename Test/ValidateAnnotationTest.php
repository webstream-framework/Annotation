<?php

namespace WebStream\Annotation\Test;

require_once dirname(__FILE__) . '/../Modules/DI/Injector.php';
require_once dirname(__FILE__) . '/../Modules/Exception/ApplicationException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/SystemException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/AnnotationException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/InvalidArgumentException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/InvalidRequestException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/ValidateException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Delegate/ExceptionDelegator.php';
require_once dirname(__FILE__) . '/../Modules/ClassLoader/ClassLoader.php';
require_once dirname(__FILE__) . '/../Modules/Container/Container.php';
require_once dirname(__FILE__) . '/../Modules/IO/File.php';
require_once dirname(__FILE__) . '/../Modules/IO/InputStream.php';
require_once dirname(__FILE__) . '/../Modules/IO/FileInputStream.php';
require_once dirname(__FILE__) . '/../Base/Annotation.php';
require_once dirname(__FILE__) . '/../Base/IAnnotatable.php';
require_once dirname(__FILE__) . '/../Base/IMethod.php';
require_once dirname(__FILE__) . '/../Reader/AnnotationReader.php';
require_once dirname(__FILE__) . '/../Attributes/Validate.php';
require_once dirname(__FILE__) . '/../Attributes/Ext/ValidateRule/IValidate.php';
require_once dirname(__FILE__) . '/../Test/Providers/ValidateAnnotationProvider.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/ValidateFixture1.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/ValidateFixture2.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/ValidateFixture3.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/ValidateFixture4.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/ValidateFixture5.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/ValidateFixture6.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/ValidateFixture7.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/ValidateFixture8.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/ValidateFixture9.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/ValidateFixture10.php';

use PHPUnit\Framework\TestCase;
use WebStream\Annotation\Attributes\Validate;
use WebStream\Annotation\Reader\AnnotationReader;
use WebStream\Annotation\Test\Providers\ValidateAnnotationProvider;
use WebStream\Container\Container;
use WebStream\Exception\Extend\ValidateException;

/**
 * ValidateAnnotationTest
 * @author Ryuichi TANAKA.
 * @since 2017/01/20
 * @version 0.7
 */
class ValidateAnnotationTest extends TestCase
{
    use ValidateAnnotationProvider;

    /**
     * 正常系
     * バリデーションルールが適用され、正常に処理されること
     * @test
     * @dataProvider okProvider
     * @param $clazz
     * @param $action
     * @param $requestMethod
     * @param $params
     */
    public function okAnnotationTest($clazz, $action, $requestMethod, $params)
    {
        $instance = new $clazz();
        $container = new Container();
        $container->request = new Container();
        $container->request->requestMethod = strtoupper($requestMethod);
        $container->request->{$requestMethod} = $params;
        $container->applicationInfo = new Container(false);
        $container->applicationInfo->applicationRoot = dirname(__FILE__) . '/../Attributes/';
        $container->applicationInfo->externalLibraryRoot = null;
        $container->logger = new class ()
        {
            function __call($name, $args)
            {
            }
        };
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($action);
        $annotationReader->readable(Validate::class, $container);
        $annotationReader->readMethod();
        $this->assertNull($annotationReader->getException());
    }

    /**
     * 異常系
     * バリデーションエラーが発生すること
     * @test
     * @dataProvider ngProvider
     * @param $clazz
     * @param $action
     * @param $requestMethod
     * @param $params
     * @param $message
     * @throws \Exception
     */
    public function ngAnnotationTest($clazz, $action, $requestMethod, $params, $message)
    {
        $instance = new $clazz();
        $container = new Container();
        $container->request = new Container();
        $container->request->requestMethod = strtoupper($requestMethod);
        $container->request->{$requestMethod} = $params;
        $container->applicationInfo = new Container(false);
        $container->applicationInfo->applicationRoot = dirname(__FILE__) . '/../Attributes/';
        $container->applicationInfo->externalLibraryRoot = null;
        $container->logger = new class ()
        {
            function __call($name, $args)
            {
            }
        };
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($action);
        $annotationReader->readable(Validate::class, $container);
        $annotationReader->readMethod();

        $exceptionMessage = null;
        try {
            $annotationReader->getException()->raise();
        } catch (ValidateException $e) {
            $exceptionMessage = $e->getMessage();
        }

        $this->assertEquals($exceptionMessage, $message);
    }
}
