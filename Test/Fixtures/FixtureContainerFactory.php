<?php
namespace WebStream\Annotation\Test\Fixtures;

use WebStream\Container\Container;

class FixtureContainerFactory
{
    public static function getHeaderFixtureContainer1($requestMethod)
    {
        // invalid request method
        $container = new Container();
        $container->requestMethod = $requestMethod;
        $container->contentType = "html";

        return $container;
    }

    public static function getFilterFixtureContainer1()
    {
        // invalid request method
        $container = new Container();
        $container->action = "action";
        return $container;
    }
}
