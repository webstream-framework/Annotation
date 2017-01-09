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
require_once dirname(__FILE__) . '/../Base/IMethods.php';
require_once dirname(__FILE__) . '/../Base/IRead.php';
require_once dirname(__FILE__) . '/../Reader/AnnotationReader.php';
require_once dirname(__FILE__) . '/../Header.php';
require_once dirname(__FILE__) . '/../Test/Providers/HeaderAnnotationProvider.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/FixtureContainerFactory.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/HeaderFixture.php';

use WebStream\Annotation\Header;
use WebStream\Annotation\Reader\AnnotationReader;
use WebStream\Annotation\Test\Fixtures\HeaderFixture;
use WebStream\Annotation\Test\Providers\HeaderAnnotationProvider;
use WebStream\Exception\Delegate\ExceptionDelegator;
use WebStream\Container\Container;

/**
 * HeaderAnnotationTest
 * @author Ryuichi TANAKA.
 * @since 2017/01/09
 * @version 0.7
 */
class HeaderAnnotationTest extends \PHPUnit_Framework_TestCase
{
    use HeaderAnnotationProvider;

    /**
     * 正常系
     * @Headerの情報が取得できること
     * @test
     * @dataProvider okProvider
     */
    public function okAnnotationTest($requestMethod)
    {
        $instance = new HeaderFixture();
        $container = new Container();
        $container->requestMethod = $requestMethod;
        $container->contentType = "html";
        $annotaionReader = new AnnotationReader($instance);
        $annotaionReader->setActionMethod("method");
        $annotaionReader->readable(Header::class, $container);
        $annotaionReader->readMethod();

        $this->assertArraySubset(
            [Header::class => [
                ['contentType' => 'html']
            ]],
            $annotaionReader->getAnnotationInfoList()
        );
    }

    /**
     * 異常系
     * エラーが発生した場合、例外オブジェクトが取得できること
     * @test
     * @dataProvider ngProvider
     */
    public function ngAnnotationTest($requestMethod)
    {
        $instance = new HeaderFixture();
        $container = new Container();
        $container->requestMethod = $requestMethod;
        $container->contentType = "html";
        $annotaionReader = new AnnotationReader($instance);
        $annotaionReader->setActionMethod("method");
        $annotaionReader->readable(Header::class, $container);
        $annotaionReader->readMethod();
        $this->assertInstanceOf(ExceptionDelegator::class, $annotaionReader->getException());
    }

    /**
     * 異常系
     * アノテーション読み込み設定をしていない場合、読み込み結果が取得できないこと
     * @test
     * @dataProvider okProvider
     */
    public function ngUnReadableAnnotationTest($requestMethod)
    {
        $instance = new HeaderFixture();
        $container = new Container();
        $container->requestMethod = $requestMethod;
        $container->contentType = "html";
        $annotaionReader = new AnnotationReader($instance);
        $annotaionReader->setActionMethod("method");
        $annotaionReader->readMethod();
        $this->assertEmpty($annotaionReader->getAnnotationInfoList());
    }
}
