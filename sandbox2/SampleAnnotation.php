<?php
namespace WebStream\Annotation;

use WebStream\Annotation\Base\Annotation;
use WebStream\Annotation\Base\IAnnotatable;
use WebStream\Annotation\Base\IMethods;
use WebStream\Annotation\Base\IRead;
use WebStream\Container\Container;

/**
 * @Annotation
 * @Target("METHOD")
 */
class SampleAnnotation extends Annotation implements IMethods, IRead
{
    private $annotations;

    /**
     * {@inheritdoc}
     */
    public function onInject(array $annotations)
    {
        // TODO バリデーションとか
        $this->annotations = $annotations;
    }

    public function getAnnotationInfo()
    {
        return "hoge";
    }

    /**
     * {@inheritdoc}
     */
    public function onMethodInject(IAnnotatable $instance, \ReflectionMethod $method, Container $container)
    {
        var_dump($instance);
        var_dump($method);
    }
}
