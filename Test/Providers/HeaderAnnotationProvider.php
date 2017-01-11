<?php
namespace WebStream\Annotation\Test\Providers;

use WebStream\Annotation\Test\Fixtures\HeaderFixture1;

/**
 * HeaderAnnotationProvider
 * @author Ryuichi TANAKA.
 * @since 2017/01/09
 * @version 0.7
 */
trait HeaderAnnotationProvider
{
    public function okProvider()
    {
        return [
            [HeaderFixture1::class, "action1", "POST"],
            [HeaderFixture1::class, "action2", "POST"]
        ];
    }

    public function ngProvider()
    {
        return [
            [HeaderFixture1::class, "action1", "GET"],
            [HeaderFixture1::class, "action1", "PUT"],
            [HeaderFixture1::class, "action1", "DELETE"]
        ];
    }
}
