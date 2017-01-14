<?php
namespace WebStream\Annotation\Test\Providers;

use WebStream\Annotation\Test\Fixtures\DatabaseFixture1;

/**
 * DatabaseAnnotationProvider
 * @author Ryuichi TANAKA.
 * @since 2017/01/14
 * @version 0.7
 */
trait DatabaseAnnotationProvider
{
    public function okProvider()
    {
        return [
            [DatabaseFixture1::class, "action1", [[
                'filename' => "test.tmpl",
                'engine' => "WebStream\Annotation\Basic"
            ]]]
        ];
    }
}
