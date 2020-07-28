<?php

namespace WebStream\Annotation\Test\Fixtures;

use WebStream\Annotation\Base\IAnnotatable;
use WebStream\Annotation\Attributes\Validate;

class ValidateFixture10 implements IAnnotatable
{
    /**
     * @Validate(key="test", rule="regexp[/\d+/]", method="get")
     */
    public function regexp1()
    {
    }

    /**
     * @Validate(key="test", rule="regexp[/\d+/]", method="post")
     */
    public function regexp2()
    {
    }

    /**
     * @Validate(key="test", rule="regexp[/\d+/]", method="put")
     */
    public function regexp3()
    {
    }

    /**
     * @Validate(key="test", rule="regexp[/\d+/]", method="delete")
     */
    public function regexp4()
    {
    }
}
