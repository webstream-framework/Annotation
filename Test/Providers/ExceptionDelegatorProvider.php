<?php
namespace WebStream\Exception\Test\Providers;

use WebStream\Exception\ApplicationException;
use WebStream\Exception\SystemException;
use WebStream\Exception\DelegateException;

/**
 * ExceptionDelegatorProvider
 * @author Ryuichi TANAKA.
 * @since 2017/01/08
 * @version 0.7
 */
trait ExceptionDelegatorProvider
{
    public function exceptionProvider()
    {
        return [
            [new SystemException("error message")],
            [new ApplicationException("error message")],
            [new DelegateException(new ApplicationException("error message"))]
        ];
    }
}
