<?php
namespace WebStream\Annotation\Test\Providers;

use WebStream\Annotation\Test\Fixtures\ValidateFixture1;

/**
 * ValidateAnnotationProvider
 * @author Ryuichi TANAKA.
 * @since 2017/01/20
 * @version 0.7
 */
trait ValidateAnnotationProvider
{
    public function okProvider()
    {
        return [
            [ValidateFixture1::class, "required"]
        ];
    }
}
