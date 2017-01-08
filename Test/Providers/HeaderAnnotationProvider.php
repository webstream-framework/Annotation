<?php
namespace WebStream\Annotation\Test\Providers;

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
            ["POST"]
        ];
    }

    public function ngProvider()
    {
        return [
            ["GET"],
            ["PUT"],
            ["DELETE"]
        ];
    }
}
