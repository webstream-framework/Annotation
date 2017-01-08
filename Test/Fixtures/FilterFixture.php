<?php
namespace WebStream\Annotation\Test\Fixtures;

use WebStream\Annotation\Base\IAnnotatable;
use WebStream\Annotation\Filter;

class FilterFixture implements IAnnotatable
{
    public function action()
    {
    }

    /**
     * @Filter(type="initialize")
     */
    public function initialize1()
    {
    }

    /**
     * @Filter(type="before")
     */
    public function before1()
    {
    }
}
