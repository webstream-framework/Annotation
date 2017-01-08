<?php
namespace WebStream\Annotation\Test;

require_once dirname(__FILE__) . '/../Modules/DI/Injector.php';
require_once dirname(__FILE__) . '/../Modules/Container/Container.php';
require_once dirname(__FILE__) . '/../Modules/Exception/SystemException.php';
require_once dirname(__FILE__) . '/../Modules/Exception/Extend/CollectionException.php';
require_once dirname(__FILE__) . '/../Base/Annotation.php';
require_once dirname(__FILE__) . '/../Base/IAnnotatable.php';
require_once dirname(__FILE__) . '/../Base/IMethod.php';
require_once dirname(__FILE__) . '/../Base/IMethods.php';
require_once dirname(__FILE__) . '/../Base/IRead.php';
require_once dirname(__FILE__) . '/../Reader/AnnotationReader.php';
require_once dirname(__FILE__) . '/../Reader/Extend/ExtendReader.php';
require_once dirname(__FILE__) . '/../Reader/Extend/FilterExtendReader.php';
require_once dirname(__FILE__) . '/../Filter.php';
require_once dirname(__FILE__) . '/../Container/AnnotationContainer.php';
require_once dirname(__FILE__) . '/../Container/AnnotationListContainer.php';
require_once dirname(__FILE__) . '/../Test/Providers/FilterAnnotationProvider.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/FixtureContainerFactory.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/FilterFixture.php';

use WebStream\Annotation\Reader\AnnotationReader;
use WebStream\Annotation\Reader\Extend\FilterExtendReader;
use WebStream\Annotation\Filter;
use WebStream\Annotation\Test\Fixtures\FixtureContainerFactory;
use WebStream\Annotation\Test\Fixtures\FilterFixture;
use WebStream\Annotation\Test\Providers\FilterAnnotationProvider;
use WebStream\Exception\Delegate\ExceptionDelegator;
use WebStream\Container\Container;

/**
 * FilterAnnotationTest
 * @author Ryuichi TANAKA.
 * @since 2017/01/09
 * @version 0.7
 */
class FilterAnnotationTest extends \PHPUnit_Framework_TestCase
{
    use FilterAnnotationProvider;

    /**
     * 正常系
     * @test
     */
    public function okAnnotationTest()
    {
        $instance = new FilterFixture();
        $container = FixtureContainerFactory::getFilterFixtureContainer1();
        $annotaionReader = new AnnotationReader($instance);
        $annotaionReader->setActionMethod("method");
        $annotaionReader->readable(Filter::class, $container);
        $annotaionReader->useExtendReader(Filter::class, FilterExtendReader::class);
        $annotaionReader->readMethod();

        $ttt = $annotaionReader->getAnnotationInfoList();
        var_dump($ttt);

        // $this->assertArraySubset(
        //     ['WebStream\Annotation\Header' => [
        //         ['contentType' => 'html']
        //     ]],
        //     $annotaionReader->getAnnotationInfoList()
        // );
    }
}
