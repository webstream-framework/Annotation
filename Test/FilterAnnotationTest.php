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
require_once dirname(__FILE__) . '/../Attributes/Filter.php';
require_once dirname(__FILE__) . '/../Container/AnnotationContainer.php';
require_once dirname(__FILE__) . '/../Container/AnnotationListContainer.php';
require_once dirname(__FILE__) . '/../Test/Providers/FilterAnnotationProvider.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/FilterFixture1.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/FilterFixture2.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/FilterFixture3.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/FilterFixture4.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/FilterFixture5.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/FilterFixture6.php';
require_once dirname(__FILE__) . '/../Test/Fixtures/FilterFixture7.php';

use PHPUnit\Framework\TestCase;
use WebStream\Annotation\Attributes\Filter;
use WebStream\Annotation\Reader\AnnotationReader;
use WebStream\Annotation\Reader\Extend\FilterExtendReader;
use WebStream\Annotation\Test\Providers\FilterAnnotationProvider;
use WebStream\Container\Container;

/**
 * FilterAnnotationTest
 * @author Ryuichi TANAKA.
 * @since 2017/01/09
 * @version 0.7
 */
class FilterAnnotationTest extends TestCase
{
    use FilterAnnotationProvider;

    /**
     * 正常系
     * before/afterフィルタが実行されること
     * @test
     * @dataProvider filterOutputProvider
     * @param $output
     * @param $clazz
     * @param $action
     * @throws \ReflectionException
     */
    public function okAnnotationTest($output, $clazz, $action)
    {
        $instance = new $clazz();
        $container = new Container();
        $container->action = $action;
        $annotationReader = new AnnotationReader($instance);
        $annotationReader->setActionMethod($action);
        $annotationReader->readable(Filter::class, $container);
        $annotationReader->useExtendReader(Filter::class, FilterExtendReader::class);
        $annotationReader->readMethod();
        $annotation = $annotationReader->getAnnotationInfoList();

        foreach ($annotation[Filter::class]->initialize as $refMethod) {
            $refMethod->invoke($instance);
        }
        foreach ($annotation[Filter::class]->before as $refMethod) {
            $refMethod->invoke($instance);
        }
        $instance->{$action}();
        foreach ($annotation[Filter::class]->after as $refMethod) {
            $refMethod->invoke($instance);
        }

        $this->expectOutputString($output);
    }
}
