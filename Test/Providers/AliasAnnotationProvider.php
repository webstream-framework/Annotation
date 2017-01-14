<?php
namespace WebStream\Annotation\Test\Providers;

use WebStream\Annotation\Test\Fixtures\TemplateFixture1;

/**
 * TemplateAnnotationProvider
 * @author Ryuichi TANAKA.
 * @since 2017/01/10
 * @version 0.7
 */
trait TemplateAnnotationProvider
{
    public function okProvider()
    {
        return [
            [TemplateFixture1::class, "aliasMethod1", "originMethod1"]
        ];
    }
}
