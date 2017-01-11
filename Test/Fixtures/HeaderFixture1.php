<?php
namespace WebStream\Annotation\Test\Fixtures;

use WebStream\Annotation\Base\IAnnotatable;
use WebStream\Annotation\Header;

class HeaderFixture1 implements IAnnotatable
{
    /**
     * @Header(allowMethod="post")
     */
    public function action1()
    {
    }

    /**
     * @Header(allowMethod="POST")
     */
    public function action2()
    {
    }
}
