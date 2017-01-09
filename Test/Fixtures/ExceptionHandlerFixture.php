<?php
namespace WebStream\Annotation\Test\Fixtures;

use WebStream\Annotation\Base\IAnnotatable;
use WebStream\Annotation\ExceptionHandler;
use WebStream\Exception\ApplicationException;

class ExceptionHandlerFixture implements IAnnotatable
{
    public function action()
    {
        throw new ApplicationException("message");
    }

    /**
     * @ExceptionHandler("WebStream\Exception\ApplicationException")
     */
    public function error($params)
    {
        echo $params["method"];
    }
}
