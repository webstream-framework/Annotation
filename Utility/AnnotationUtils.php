<?php
namespace WebStream\Annotation\Utility;

/**
 * AnnotationUtils
 * @author Ryuichi Tanaka
 * @since 2015/12/26
 * @version 0.7
 */
trait AnnotationUtils
{
    /**
     * 要素が存在するかどうか
     * @param array 検索対象配列
     * @param mixed 検索値
     * @return bool 存在すればtrue
     */
    public function inArray($target, $list)
    {
        $type = gettype($target);
        switch ($type) {
            case "string":
            case "integer":
                return array_key_exists($target, array_flip($list));
            default:
                // それ以外の場合、in_arrayを使用する
                return in_array($target, $list, true);
        }
    }
}
