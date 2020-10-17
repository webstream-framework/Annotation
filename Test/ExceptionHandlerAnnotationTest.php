<?php

namespace WebStream\Annotation\Test;

require_once dirname(__FILE__) . '/../Modules/DI/Injector.php';
require_once dirname(__FILE__) . '/../Modules/Container/Container.php';
require_once dirname(__FILE__) . '/../Base/Annotation.php';
require_once dirname(__FILE__) . '/../Base/IAnnotatable.php';
require_once dirname(__FILE__) . '/../Base/IMethod.php';
require_once dirname(__FILE__) . '/../Base/IMethods.php';
require_once dirname(__FILE__) . '/../Base/IRead.php';
require_once dirname(__FILE__) . '/../Reader/AnnotationReader.php';
require_once dirname(__FILE__) . '/../Attributes/ExceptionHandler.php';
require_once dirname(__FILE__) . '/../Test/Providers/ExceptionHandlerAnnotationProvider.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/ExceptionHandlerFixture1.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/ExceptionHandlerFixture2.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/ExceptionHandlerFixture3.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/ExceptionHandlerFixture4.php';

use PHPUnit\Framework\TestCase;
use WebStream\Annotation\Reader\AnnotationReader;
use WebStream\Annotation\Attributes\ExceptionHandler;
use WebStream\Annotation\Test\Providers\ExceptionHandlerAnnotationProvider;
use WebStream\Container\Container;

/**
 * ExceptionHandlerAnnotationTest
 * @author Ryuichi TANAKA.
 * @since 2017/01/09
 * @version 0.7
 */
class ExceptionHandlerAnnotationTest extends TestCase
{
    use ExceptionHandlerAnnotationProvider;

    /**
     * 正常系
     * 発生した例外を捕捉できること
     * @test
     * @dataProvider okProvider
     * @param $fixtureClass
     * @param $exceptionClass
     * @param $action
     * @throws \ReflectionException
     */
    public function okAnnotationTest($fixtureClass, $exceptionClass, $action)
    {
        $instance = new $fixtureClass();
        $container = new Container();
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($action);
        $annotationReader->readable(ExceptionHandler::class, $container);
        $annotationReader->readMethod();
        $annotation = $annotationReader->getAnnotationInfoList();

        $this->assertTrue(count($annotation[ExceptionHandler::class]) > 0);
        $exceptions = [];
        foreach ($annotation[ExceptionHandler::class] as $handledException) {
            $exceptions = array_merge($exceptions, $handledException['exceptions']);
        }
        $this->assertTrue(in_array($exceptionClass, $exceptions));
    }
}
