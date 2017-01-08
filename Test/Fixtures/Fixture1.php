<?php
namespace WebStream\Annotation\Test\Fixtures;

use WebStream\Annotation\Base\IAnnotatable;
use WebStream\Annotation\Header;

class Fixture1 implements IAnnotatable
{
    /**
     * @Header(allowMethod="post")
     */
    public function header()
    {

    }
}
