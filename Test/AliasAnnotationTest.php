<?php

namespace WebStream\Annotation\Test;

require_once dirname(__FILE__) . '/../Modules/DI/Injector.php';
require_once dirname(__FILE__) . '/../Modules/Container/Container.php';
require_once dirname(__FILE__) . '/../Modules/Exception/ApplicationException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/AnnotationException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Delegate/ExceptionDelegator.php';
require_once dirname(__FILE__) . '/../Base/Annotation.php';
require_once dirname(__FILE__) . '/../Base/IAnnotatable.php';
require_once dirname(__FILE__) . '/../Base/IMethods.php';
require_once dirname(__FILE__) . '/../Base/IRead.php';
require_once dirname(__FILE__) . '/../Reader/AnnotationReader.php';
require_once dirname(__FILE__) . '/../Attributes/Alias.php';
require_once dirname(__FILE__) . '/../Test/Providers/AliasAnnotationProvider.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/AliasFixture1.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/AliasFixture2.php';

use PHPUnit\Framework\TestCase;
use WebStream\Annotation\Attributes\Alias;
use WebStream\Annotation\Reader\AnnotationReader;
use WebStream\Annotation\Test\Providers\AliasAnnotationProvider;
use WebStream\Container\Container;

/**
 * AliasAnnotationTest
 * @author Ryuichi TANAKA.
 * @since 2017/01/10
 * @version 0.7
 */
class AliasAnnotationTest extends TestCase
{
    use AliasAnnotationProvider;

    /**
     * 正常系
     * メソッドエイリアスが定義されている実メソッドが取得できること
     * @test
     * @dataProvider okProvider
     * @param $clazz
     * @param $aliasMethod
     * @param $originMethod
     * @throws \ReflectionException
     */
    public function okAnnotationTest($clazz, $aliasMethod, $originMethod)
    {
        $instance = new $clazz();
        $container = new Container();
        $container->action = $aliasMethod;
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($aliasMethod);
        $annotationReader->readable(Alias::class, $container);
        $annotationReader->readMethod();
        $annotation = $annotationReader->getAnnotationInfoList();

        $this->assertEquals($annotation[Alias::class][0]['method'], $originMethod);
    }

    /**
     * 異常系
     * メソッドエイリアスと同名の実メソッドが定義されている場合、例外が発生すること
     * @test
     * @dataProvider ngProvider
     * @param $clazz
     * @param $aliasMethod
     * @throws \Exception
     */
    public function ngAnnotationTest($clazz, $aliasMethod)
    {
        $this->expectException(\WebStream\Exception\Extend\AnnotationException::class);
        $instance = new $clazz();
        $container = new Container();
        $container->action = $aliasMethod;
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($aliasMethod);
        $annotationReader->readable(Alias::class, $container);
        $annotationReader->readMethod();
        $exception = $annotationReader->getException();

        $this->assertNotNull($exception);
        $exception->raise();
    }
}
