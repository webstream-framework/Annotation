<?php
namespace WebStream\Annotation\Test;

require_once dirname(__FILE__) . '/../Modules/DI/Injector.php';
// require_once dirname(__FILE__) . '/../Modules/Exception/ApplicationException.php';
// require_once dirname(__FILE__) . '/../Modules/Exception/SystemException.php';
// require_once dirname(__FILE__) . '/../Modules/Exception/Extend/AnnotationException.php';
// require_once dirname(__FILE__) . '/../Modules/Exception/Extend/InvalidArgumentException.php';
// require_once dirname(__FILE__) . '/../Modules/Exception/Extend/InvalidRequestException.php';
// require_once dirname(__FILE__) . '/../Modules/Exception/Delegate/ExceptionDelegator.php';
require_once dirname(__FILE__) . '/../Modules/Container/Container.php';
require_once dirname(__FILE__) . '/../Base/Annotation.php';
require_once dirname(__FILE__) . '/../Base/IAnnotatable.php';
require_once dirname(__FILE__) . '/../Base/IMethod.php';
require_once dirname(__FILE__) . '/../Reader/AnnotationReader.php';
require_once dirname(__FILE__) . '/../Validate.php';
require_once dirname(__FILE__) . '/../Test/Providers/ValidateAnnotationProvider.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/ValidateFixture1.php';

use WebStream\Annotation\Validate;
use WebStream\Annotation\Reader\AnnotationReader;
use WebStream\Annotation\Test\Providers\ValidateAnnotationProvider;
use WebStream\Container\Container;

/**
 * ValidateAnnotationTest
 * @author Ryuichi TANAKA.
 * @since 2017/01/20
 * @version 0.7
 */
class ValidateAnnotationTest extends \PHPUnit_Framework_TestCase
{
    use ValidateAnnotationProvider;

    /**
     * 正常系
     * @Headerの情報が取得できること
     * @test
     * @dataProvider okProvider
     */
    public function okAnnotationTest($clazz, $action)
    {
        $instance = new $clazz();
        $container = new Container();
        // $container->requestMethod = $requestMethod;
        // $container->contentType = $contentType;
        $annotaionReader = new AnnotationReader($instance);
        $annotaionReader->setActionMethod($action);
        $annotaionReader->readable(Validate::class, $container);
        $annotaionReader->readMethod();
        var_dump($annotaionReader->getAnnotationInfoList());

        // $this->assertArraySubset(
        //     [Header::class => [
        //         ['contentType' => $contentType]
        //     ]],
        //     $annotaionReader->getAnnotationInfoList()
        // );
    }
}
