<?php

namespace WebStream\Annotation\Attributes\Ext\ValidateRule;

/**
 * Range
 * @author Ryuichi TANAKA.
 * @since 2015/03/30
 * @version 0.4
 */
class Range implements IValidate
{
    /**
     * {@inheritdoc}
     */
    public function isValid($value, string $rule)
    {
        $isValid = false;
        if (preg_match('/^range\[([-]?\d+\.?\d*?)\.\.([-]?\d+\.?\d*?)\]$/', $rule, $matches)) {
            $value = doubleval($value);
            $isValid = $value === null || ($value >= doubleval($matches[1]) && $value <= doubleval($matches[2]));
        }

        return $isValid;
    }
}
