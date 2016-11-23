<?php

$annotaionReader = new AnnotationReader($instance);
$annotaionReader->readable("\WebStream\Annotation\Database", $container) // 第二引数はAnnotationで依存しているデータ
                ->readable("\WebStream\Annotation\Header");

$annotaionReader->read();

// 結果はリストで返す
// $list = ["AnnotaionName"]["Annotated(Class|Method|Property)Name"][$i]
$annotationInfoAllList = $annotaionReader->getInjectedAnnotationInfo();
$annotationInfoList = $annotaionReader->getInjectedAnnotationInfo("\WebStream\Annotation\Database");

$exteption = $annotaionReader->getException();


$hoge = new Hoge();
$hoge->inject('logger', $logger);
// $hoge->inject('logger', $nullableLogger, true);
$hoge->inject('logger', $logger, '\WebStream\Logger\Logger');
