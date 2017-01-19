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
        $func = function ($queryKey, $xpath) use ($annotationInfoList) {
            $query = null;
            foreach ($annotationInfoList as $annotationInfo) {
                $queryFunctions = $annotationInfo[$queryKey];
                if ($queryFunctions === null) {
                    continue;
                }
                foreach ($queryFunctions as $queryFunction) {
                    $xmlObjectList = $queryFunction->fetch();
                    foreach ($xmlObjectList as $xmlObject) {
                        if ($xmlObject !== null) {
                            $xmlElement = $xmlObject->xpath($xpath);
                            if (!empty($xmlElement)) {
                                $query = ["sql" => trim($xmlElement[0]->__toString()), "method" => $xmlElement[0]->getName()];
                                $entity = $xmlElement[0]->attributes()["entity"];
                                $query["entity"] = $entity !== null ? $entity->__toString() : null;
                                break;
                            }
                        }
                    }
                }
            }

            return $query;
        };

        $this->annotationInfo = function ($queryKey, $xpath) use ($func) {
            return $func($queryKey, $xpath);
        };
    }
}
