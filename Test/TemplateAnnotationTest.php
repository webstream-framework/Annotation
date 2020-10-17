<?php

namespace WebStream\Annotation\Test;

require_once dirname(__FILE__) . '/../Modules/DI/Injector.php';
require_once dirname(__FILE__) . '/../Modules/Container/Container.php';
require_once dirname(__FILE__) . '/../Modules/Exception/ApplicationException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/AnnotationException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Delegate/ExceptionDelegator.php';
require_once dirname(__FILE__) . '/../Base/Annotation.php';
require_once dirname(__FILE__) . '/../Base/IAnnotatable.php';
require_once dirname(__FILE__) . '/../Base/IMethod.php';
require_once dirname(__FILE__) . '/../Base/IRead.php';
require_once dirname(__FILE__) . '/../Reader/AnnotationReader.php';
require_once dirname(__FILE__) . '/../Attributes/Template.php';
require_once dirname(__FILE__) . '/../Test/Providers/TemplateAnnotationProvider.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/TemplateFixture1.php';

use PHPUnit\Framework\TestCase;
use WebStream\Annotation\Attributes\Template;
use WebStream\Annotation\Reader\AnnotationReader;
use WebStream\Annotation\Test\Providers\TemplateAnnotationProvider;
use WebStream\Container\Container;

/**
 * TemplateAnnotationTest
 * @author Ryuichi TANAKA.
 * @since 2017/01/14
 * @version 0.7
 */
class TemplateAnnotationTest extends TestCase
{
    use TemplateAnnotationProvider;

    /**
     * 正常系
     * データベース情報を読み込めること
     * @test
     * @dataProvider okProvider
     * @param $clazz
     * @param $action
     * @param $result
     * @throws \ReflectionException
     */
    public function okAnnotationTest($clazz, $action, $result)
    {
        $instance = new $clazz();
        $container = new Container();
        $container->action = $action;
        $container->engine = [
            'basic' => "WebStream\Template\Basic",
            'twig' => "WebStream\Template\Twig"
        ];
        $container->logger = new class ()
        {
            function __call($name, $args)
            {
            }
        };
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($action);
        $annotationReader->readable(Template::class, $container);
        $annotationReader->readMethod();

        $this->assertEquals(
            [Template::class => $result],
            $annotationReader->getAnnotationInfoList()
        );
    }

    /**
     * 異常系
     * テンプレート情報に誤りがある場合、例外が発生すること
     * @test
     * @dataProvider ngProvider
     * @param $clazz
     * @param $action
     * @throws \Exception
     */
    public function ngAnnotationTest($clazz, $action)
    {
        $this->expectException(\WebStream\Exception\Extend\AnnotationException::class);
        $instance = new $clazz();
        $container = new Container();
        $container->action = $action;
        $container->engine = [
            'basic' => "WebStream\Template\Basic",
            'twig' => "WebStream\Template\Twig"
        ];
        $container->logger = new class ()
        {
            function __call($name, $args)
            {
            }
        };
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($action);
        $annotationReader->readable(Template::class, $container);
        $annotationReader->readMethod();
        $exception = $annotationReader->getException();

        $this->assertNotNull($exception);
        $exception->raise();
    }
}
