<?php
namespace WebStream\Annotation\Test;

require_once dirname(__FILE__) . '/../Modules/DI/Injector.php';
require_once dirname(__FILE__) . '/../Modules/Container/Container.php';
require_once dirname(__FILE__) . '/../Modules/IO/File.php';
require_once dirname(__FILE__) . '/../Modules/Exception/ApplicationException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/DatabaseException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/IOException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Delegate/ExceptionDelegator.php';
require_once dirname(__FILE__) . '/../Base/Annotation.php';
require_once dirname(__FILE__) . '/../Base/IAnnotatable.php';
require_once dirname(__FILE__) . '/../Base/IClass.php';
require_once dirname(__FILE__) . '/../Base/IRead.php';
require_once dirname(__FILE__) . '/../Reader/AnnotationReader.php';
require_once dirname(__FILE__) . '/../Database.php';
require_once dirname(__FILE__) . '/../Test/Providers/DatabaseAnnotationProvider.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/DatabaseFixture1.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/DatabaseDriverFixture.php';

use WebStream\Annotation\Reader\AnnotationReader;
use WebStream\Annotation\Database;
use WebStream\Annotation\Test\Providers\DatabaseAnnotationProvider;
use WebStream\Container\Container;
use WebStream\Exception\Extend\AnnotationException;

/**
 * DatabaseAnnotationTest
 * @author Ryuichi TANAKA.
 * @since 2017/01/14
 * @version 0.7
 */
class DatabaseAnnotationTest extends \PHPUnit_Framework_TestCase
{
    use DatabaseAnnotationProvider;

    /**
     * 正常系
     * @test
     * @dataProvider okProvider
     */
    public function okAnnotationTest($clazz, $action, $configPath, $result)
    {
        $instance = new $clazz();
        $container = new Container();
        $container->action = $action;
        $container->configPath = $configPath;
        $annotaionReader = new AnnotationReader($instance);
        $annotaionReader->setActionMethod($action);
        $annotaionReader->readable(Database::class, $container);
        $annotaionReader->readClass();
        $annotation = $annotaionReader->getAnnotationInfoList();

        var_dump(realpath(dirname(__FILE__) . "/../Fixtures/DatabaseFixture1.php"));

        var_dump($annotation);
    }
}
