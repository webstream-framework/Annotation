<?php
namespace WebStream\Annotation;

use WebStream\Annotation\Base\Annotation;
use WebStream\Annotation\Base\IAnnotatable;
use WebStream\Annotation\Base\IRead;
use WebStream\Annotation\Base\IMethods;
use WebStream\Annotation\container\AnnotationListContainer;
use WebStream\Container\Container;
use WebStream\Exception\Extend\DatabaseException;
use WebStream\IO\File;
use WebStream\IO\FileInputStream;

/**
 * Query
 * @author Ryuichi TANAKA.
 * @since 2013/10/20
 * @version 0.4
 *
 * @Annotation
 * @Target("METHOD")
 */
class Query extends Annotation implements IMethods, IRead
{
    /**
     * @var array<string> 注入アノテーション情報
     */
    private $injectAnnotation;

    /**
     * @var array<string> 読み込みアノテーション情報
     */
    private $readAnnotation;

    /**
     * {@inheritdoc}
     */
    public function onInject(array $injectAnnotation)
    {
        $this->injectAnnotation = $injectAnnotation;
        $this->readAnnotation = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAnnotationInfo(): array
    {
        return $this->readAnnotation;
    }

    /**
     * {@inheritdoc}
     */
    public function onMethodInject(IAnnotatable $instance, \ReflectionMethod $method, Container $container)
    {
        $key = $method->class . "#" . $method->name;
        if (!array_key_exists($key, $this->readAnnotation)) {
            $this->readAnnotation[$key] = new AnnotationListContainer();
        }

        if (!array_key_exists('file', $this->injectAnnotation)) {
            throw new AnnotationException("'file' attribute must be required in @Query");
        }

        $files = [];
        $filePath = $this->injectAnnotation['file'];
        if (!is_array($filePath)) {
            $filePath = [$filePath];
        }

        foreach ($filePath as $file) {
            $file = new File($container->rootPath . '/' . $file);
            if (!$file->isFile()) {
                throw new DatabaseException("Failded to read query file: " . $file->getFilePath());
            }
            $files[] = $file;
        }

        $this->readAnnotation[$key]->pushAsLazy(function () use ($files) {
            $xmlObjectList = [];
            foreach ($files as $file) {
                if ($file->isFile()) {
                    $xmlObject = simplexml_load_file($file->getAbsoluteFilePath());
                    if ($xmlObject === false) {
                        throw new DatabaseException("Failded to parse query file: " . $file->getFilePath());
                    }

                    $stream = new FileInputStream($file);
                    // TODO namespaceをとる。ReadLineでマッチしたところで終了




                    // $xmlElement = $xmlObject->xpath("//mapper[@namespace='$namespace']/*[@id='$method']");
                    // if (!empty($xmlElement)) {
                    //     $query = ["sql" => trim($xmlElement[0]->__toString()), "method" => $xmlElement[0]->getName()];
                    //     $entity = $xmlElement[0]->attributes()["entity"];
                    //     $query["entity"] = $entity !== null ? $entity->__toString() : null;
                    //     break;
                    // }

                    $xmlObjectList[] = $xmlObject;
                }
            }

            return $xmlObjectList;
        });
    }
}
