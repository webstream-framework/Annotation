<?php
namespace WebStream\Annotation\Test\Fixtures;

use WebStream\Annotation\Base\IAnnotatable;
use WebStream\Annotation\Query;

class QueryFixture2 implements IAnnotatable
{
    /**
     * @Query(file="database-mapper-invalid.xml")
     */
    public function action1()
    {
    }
}
