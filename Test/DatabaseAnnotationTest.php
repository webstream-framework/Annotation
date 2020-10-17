<?php

namespace WebStream\Annotation\Test;

require_once dirname(__FILE__) . '/../Modules/Container/Container.php';
require_once dirname(__FILE__) . '/../Modules/IO/File.php';
require_once dirname(__FILE__) . '/../Modules/DI/Injector.php';
require_once dirname(__FILE__) . '/../Modules/Exception/ApplicationException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/DatabaseException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/IOException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Delegate/ExceptionDelegator.php';
require_once dirname(__FILE__) . '/../Base/Annotation.php';
require_once dirname(__FILE__) . '/../Base/IAnnotatable.php';
require_once dirname(__FILE__) . '/../Base/IClass.php';
require_once dirname(__FILE__) . '/../Base/IRead.php';
require_once dirname(__FILE__) . '/../Reader/AnnotationReader.php';
require_once dirname(__FILE__) . '/../Attributes/Database.php';
require_once dirname(__FILE__) . '/../Test/Providers/DatabaseAnnotationProvider.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/DatabaseFixture1.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/DatabaseFixture2.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/DatabaseDriverFixture.php';

use PHPUnit\Framework\TestCase;
use WebStream\Annotation\Attributes\Database;
use WebStream\Annotation\Reader\AnnotationReader;
use WebStream\Annotation\Test\Providers\DatabaseAnnotationProvider;
use WebStream\Container\Container;

/**
 * DatabaseAnnotationTest
 * @author Ryuichi TANAKA.
 * @since 2017/01/14
 * @version 0.7
 */
class DatabaseAnnotationTest extends TestCase
{
    use DatabaseAnnotationProvider;

    /**
     * 正常系
     * テンプレート情報を読み込めること
     * @test
     * @dataProvider okProvider
     * @param $clazz
     * @param $action
     * @param $rootPath
     * @param $result
     * @throws \ReflectionException
     */
    public function okAnnotationTest($clazz, $action, $rootPath, $result)
    {
        $instance = new $clazz();
        $container = new Container();
        $container->rootPath = $rootPath;
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($action);
        $annotationReader->readable(Database::class, $container);
        $annotationReader->readClass();

        $this->assertEquals(
            [Database::class => $result],
            $annotationReader->getAnnotationInfoList()
        );
    }

    /**
     * 異常系
     * データベースドライバが読み込めない場合、例外が発生すること
     * @test
     * @dataProvider ngProvider
     * @param $clazz
     * @param $action
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function ngAnnotationTest($clazz, $action)
    {
        $this->expectException(\WebStream\Exception\Extend\DatabaseException::class);
        $instance = new $clazz();
        $container = new Container();
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($action);
        $annotationReader->readable(Database::class, $container);
        $annotationReader->readClass();
        $exception = $annotationReader->getException();

        $this->assertNotNull($exception);
        $exception->raise();
    }
}
