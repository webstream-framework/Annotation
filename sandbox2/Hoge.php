<?php
namespace WebStream\Test;

use WebStream\Annotation\Base\IAnnotatable;
use WebStream\Annotation\Header;

class Hoge implements IAnnotatable
{
    /**
     * @Header(allowMethod="post")
     */
    public function header()
    {

    }
}
