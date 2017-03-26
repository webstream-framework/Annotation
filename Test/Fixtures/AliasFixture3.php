<?php
namespace WebStream\Annotation\Test\Fixtures;

use WebStream\Annotation\Base\IAnnotatable;
use WebStream\Annotation\Alias;

/**
 * 実メソッドにメソッドエイリアスからアクセスできること
 */
class AliasFixture3 implements IAnnotatable
{
    /**
     * @Alias(name="12345")
     */
    public function originMethod1()
    {
    }
}
