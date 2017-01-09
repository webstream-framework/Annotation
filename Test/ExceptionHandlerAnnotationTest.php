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
require_once dirname(__FILE__) . '/../ExceptionHandler.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/ExceptionHandlerFixture.php';

use WebStream\Annotation\Reader\AnnotationReader;
use WebStream\Annotation\ExceptionHandler;
use WebStream\Annotation\Test\Fixtures\ExceptionHandlerFixture;
use WebStream\Container\Container;

class ExceptionHandlerAnnotationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function okTest()
    {
        $instance = new ExceptionHandlerFixture();
        $container = new Container();
        $annotaionReader = new AnnotationReader($instance);
        $annotaionReader->setActionMethod("action");
        $annotaionReader->readable(ExceptionHandler::class, $container);
        // $annotaionReader->useExtendReader(Filter::class, FilterExtendReader::class);
        $annotaionReader->readMethod();
        $annotation = $annotaionReader->getAnnotationInfoList();

        foreach ($annotation[ExceptionHandler::class] as $handledException) {
            var_dump($handledException['exceptions'][0]);
        }
    }
}
