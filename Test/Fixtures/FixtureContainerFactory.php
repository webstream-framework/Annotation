<?php
namespace WebStream\Annotation\Test\Fixtures;

use WebStream\Container\Container;

class FixtureContainerFactory
{
    public static function getFixture1Container1($requestMethod)
    {
        // invalid request method
        $container = new Container();
        $container->request = new Container();
        $container->request->requestMethod = $requestMethod;
        $container->request->contentType = "html";

        return $container;
    }
}
