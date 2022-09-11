<?php

namespace App;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;

class ImgResizer
{
    private array $config;
    private Imagine $imagine;

    public function __construct(array $imgResizerConfig, Imagine $imagine)
    {
        $this->config = $imgResizerConfig;
        $this->imagine = $imagine;
    }

    public function resize(string $sourceFileName): string
    {
        $image = $this->imagine->open($sourceFileName);
        $box = $this->prepareNewImageBox($image);
        $savePath = $this->prepareSavePath($sourceFileName);

        $image->resize($box)->save($savePath);

        return $savePath;
    }

    private function prepareNewImageBox(ImageInterface $image): BoxInterface
    {
        $width = $image->getSize()->getWidth();
        $height = $image->getSize()->getHeight();

        $ratio = $width / $height;

        if ($ratio > 1) {
            $newWidth = $this->config['maxLongestSideSize'];
            $newHeight = ($this->config['maxLongestSideSize'] / $ratio);
        } else {
            $newHeight = $this->config['maxLongestSideSize'];
            $newWidth = ($this->config['maxLongestSideSize'] * $ratio);
        }

        return new Box($newWidth, $newHeight);
    }

    private function prepareSavePath(string $filename): string
    {
        return $this->prepareTempDirectory(). basename($filename);
    }

    private function prepareTempDirectory(): string
    {
        return sys_get_temp_dir() . DIRECTORY_SEPARATOR . $this->config['tempSubdirectory'] . DIRECTORY_SEPARATOR;
    }
}