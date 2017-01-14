<?php
namespace WebStream\Annotation\Test\Fixtures;

use WebStream\Annotation\Base\IAnnotatable;
use WebStream\Annotation\Database;

/**
 * @Database(driver="WebStream\Annotation\Test\Fixtures\DatabaseDriverFixture", config="config/database.mysql.ini")
 */
class DatabaseFixture1 implements IAnnotatable
{
}
