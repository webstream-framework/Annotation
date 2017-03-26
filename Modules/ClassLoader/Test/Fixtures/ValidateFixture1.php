<?php
namespace WebStream\Annotation\Test\Fixtures;

use WebStream\Annotation\Base\IAnnotatable;
use WebStream\Annotation\Validate;

class ValidateFixture1 implements IAnnotatable
{
    /**
     * @Validate(key="test", rule="required", method="get")
     */
    public function required()
    {
    }
}
