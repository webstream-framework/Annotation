<?php
namespace WebStream\Test;

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../Modules/AnnotationException.php';
require_once dirname(__FILE__) . '/../Modules/InvalidRequestException.php';
require_once dirname(__FILE__) . '/../Modules/DI/Injector.php';
require_once dirname(__FILE__) . '/../Modules/Container/Container.php';
require_once dirname(__FILE__) . '/../Base/Annotation.php';
require_once dirname(__FILE__) . '/../Base/IAnnotatable.php';
require_once dirname(__FILE__) . '/../Base/IMethod.php';
require_once dirname(__FILE__) . '/../Base/IMethods.php';
require_once dirname(__FILE__) . '/../Base/IRead.php';
require_once dirname(__FILE__) . '/../Reader/AnnotationReader2.php';
require_once dirname(__FILE__) . '/../Header.php';
// require_once dirname(__FILE__) . '/SampleAnnotation.php';
require_once dirname(__FILE__) . '/Hoge.php';

use WebStream\Annotation\Reader\AnnotationReader;
use WebStream\Container\Container;

$instance = new Hoge();
$container = new Container();
$container->request = new Container();
$container->request->requestMethod = "get";
// $container->name = "alice";
$annotaionReader = new AnnotationReader($instance);
// $annotaionReader->readable("\WebStream\Annotation\SampleAnnotation", $container);
$annotaionReader->readable("WebStream\Annotation\Header", $container);
$annotaionReader->read();

var_dump($annotaionReader->getException());
var_dump($annotaionReader->getAnnotationInfoList());

// 結果はリストで返す
// $list = ["AnnotaionName"]["Annotated(Class|Method|Property)Name"][$i]
// $annotationInfoAllList = $annotaionReader->getInjectedAnnotationInfo();
// $annotationInfoList = $annotaionReader->getInjectedAnnotationInfo("\WebStream\Annotation\Database");

// $exteption = $annotaionReader->getException();
