<?php
namespace WebStream\Annotation\Reader\Extend;

use WebStream\Container\Container;

/**
 * QueryExtendReader
 * @author Ryuichi Tanaka
 * @since 2017/01/16
 * @version 0.7
 */
class QueryExtendReader extends ExtendReader
{
    /**
     * @var array<Container> アノテーション情報リスト
     */
    private $annotationInfo;

    /**
     * {@inheritdoc}
     */
    public function getAnnotationInfo()
    {
        return $this->annotationInfo;
    }

    /**
     * {@inheritdoc}
     */
    public function read(array $annotationInfoList)
    {
        var_dump($annotationInfoList);
    }
}
