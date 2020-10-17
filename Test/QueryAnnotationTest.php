<?php

namespace WebStream\Annotation\Test;

require_once dirname(__FILE__) . '/../Modules/DI/Injector.php';
require_once dirname(__FILE__) . '/../Modules/Container/Container.php';
require_once dirname(__FILE__) . '/../Modules/Container/ValueProxy.php';
require_once dirname(__FILE__) . '/../Modules/Exception/ApplicationException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/AnnotationException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/DatabaseException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Delegate/ExceptionDelegator.php';
require_once dirname(__FILE__) . '/../Modules/IO/InputStream.php';
require_once dirname(__FILE__) . '/../Modules/IO/File.php';
require_once dirname(__FILE__) . '/../Modules/IO/FileInputStream.php';
require_once dirname(__FILE__) . '/../Modules/IO/Reader/InputStreamReader.php';
require_once dirname(__FILE__) . '/../Base/Annotation.php';
require_once dirname(__FILE__) . '/../Base/IAnnotatable.php';
require_once dirname(__FILE__) . '/../Base/IMethods.php';
require_once dirname(__FILE__) . '/../Base/IRead.php';
require_once dirname(__FILE__) . '/../Reader/AnnotationReader.php';
require_once dirname(__FILE__) . '/../Reader/Extend/ExtendReader.php';
require_once dirname(__FILE__) . '/../Reader/Extend/QueryExtendReader.php';
require_once dirname(__FILE__) . '/../Container/AnnotationContainer.php';
require_once dirname(__FILE__) . '/../Container/AnnotationListContainer.php';
require_once dirname(__FILE__) . '/../Attributes/Query.php';
require_once dirname(__FILE__) . '/../Test/Providers/QueryAnnotationProvider.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/QueryFixture1.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/QueryFixture2.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/QueryFixture3.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/QueryFixture4.php';

use PHPUnit\Framework\TestCase;
use WebStream\Annotation\Attributes\Query;
use WebStream\Annotation\Reader\AnnotationReader;
use WebStream\Annotation\Reader\Extend\QueryExtendReader;
use WebStream\Annotation\Test\Providers\QueryAnnotationProvider;
use WebStream\Container\Container;

/**
 * QueryAnnotationTest
 * @author Ryuichi TANAKA.
 * @since 2017/01/15
 * @version 0.7
 */
class QueryAnnotationTest extends TestCase
{
    use QueryAnnotationProvider;

    /**
     * 正常系
     * クエリ情報を読み込めること
     * @test
     * @dataProvider ok1Provider
     * @param $clazz
     * @param $action
     * @param $rootPath
     * @param $key
     * @param $result
     * @throws \ReflectionException
     */
    public function okAnnotationTest($clazz, $action, $rootPath, $key, $result)
    {
        $instance = new $clazz();
        $container = new Container();
        $container->rootPath = $rootPath;
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($action);
        $annotationReader->readable(Query::class, $container);
        $annotationReader->useExtendReader(Query::class, QueryExtendReader::class);
        $annotationReader->readMethod();
        $annotations = $annotationReader->getAnnotationInfoList();
        $namespace = "WebStream\Annotation\Test";
        $method = $key;
        $queryKey = "WebStream\Annotation\Test\Fixtures\QueryFixture1#action1";
        $xpath = "//mapper[@namespace='$namespace']/*[@id='$method']";

        $this->assertEquals($annotations[Query::class]($queryKey, $xpath), $result);
    }

    /**
     * 正常系
     * クエリ情報を複数読み込めること
     * @test
     * @dataProvider ok2Provider
     * @param $clazz
     * @param $action
     * @param $rootPath
     * @param $key
     * @param $result
     * @throws \ReflectionException
     */
    public function okMultipleAnnotationTest($clazz, $action, $rootPath, $key, $result)
    {
        $instance = new $clazz();
        $container = new Container();
        $container->rootPath = $rootPath;
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($action);
        $annotationReader->readable(Query::class, $container);
        $annotationReader->useExtendReader(Query::class, QueryExtendReader::class);
        $annotationReader->readMethod();
        $annotations = $annotationReader->getAnnotationInfoList();
        $namespace = "WebStream\Annotation\Test";
        $method = $key;
        $queryKey = "WebStream\Annotation\Test\Fixtures\QueryFixture4#action1";
        $xpath = "//mapper[@namespace='$namespace']/*[@id='$method']";

        $this->assertEquals($annotations[Query::class]($queryKey, $xpath), $result);
    }

    /**
     * 異常系
     * クエリファイルパスが間違っている場合、例外が発生すること
     * @test
     * @dataProvider ng1Provider
     * @param $clazz
     * @param $action
     * @param $rootPath
     * @throws \Exception
     */
    public function ngAnnotationInvalidFileFormatTest($clazz, $action, $rootPath)
    {
        $this->expectException(\WebStream\Exception\Extend\DatabaseException::class);
        $instance = new $clazz();
        $container = new Container();
        $container->rootPath = $rootPath;
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($action);
        $annotationReader->readable(Query::class, $container);
        $annotationReader->useExtendReader(Query::class, QueryExtendReader::class);
        $annotationReader->readMethod();
        $exception = $annotationReader->getException();

        $this->assertNotNull($exception);
        $exception->raise();
    }

    /**
     * 異常系
     * クエリファイルパスが間違っている場合、例外が発生すること
     * @test
     * @dataProvider ng2Provider
     * @param $clazz
     * @param $action
     * @param $rootPath
     * @throws \Exception
     */
    public function ngAnnotationInvalidFilePathTest($clazz, $action, $rootPath)
    {
        $this->expectException(\WebStream\Exception\Extend\DatabaseException::class);
        $instance = new $clazz();
        $container = new Container();
        $container->rootPath = $rootPath;
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($action);
        $annotationReader->readable(Query::class, $container);
        $annotationReader->useExtendReader(Query::class, QueryExtendReader::class);
        $annotationReader->readMethod();
        $exception = $annotationReader->getException();

        $this->assertNotNull($exception);
        $exception->raise();
    }
}
