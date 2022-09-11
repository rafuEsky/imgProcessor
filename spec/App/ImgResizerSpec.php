<?php

namespace spec\App;

use App\ImgResizer;
use Imagine\Gd\Imagine;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\ManipulatorInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ImgResizerSpec extends ObjectBehavior
{
    const IMAGE_NAME = 'file.jpg';
    const IMAGE_PATH = '/full/path/to/' . self::IMAGE_NAME;
    const CONFIG = ['maxLongestSideSize' => 150, 'tempSubdirectory' => 'imgProcessor'];

    function it_is_initializable(Imagine $imagine)
    {
        $this->beConstructedWith(self::CONFIG, $imagine);
        $this->shouldHaveType(ImgResizer::class);
    }

    function it_resize_image(Imagine $imagine, ImageInterface $image, BoxInterface $sourceBox, BoxInterface $destBox, ManipulatorInterface $manipulator)
    {
        $sourceBox->getWidth()->willReturn(100);
        $sourceBox->getHeight()->willReturn(100);
        $image->getSize()->willReturn($sourceBox);
        $imagine->open(self::IMAGE_PATH)->willReturn($image);

        $savePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            self::CONFIG['tempSubdirectory'] . DIRECTORY_SEPARATOR .
            self::IMAGE_NAME;

        $manipulator->save($savePath)->shouldBeCalled();

        $image->resize(Argument::type('Imagine\Image\BoxInterface'))->willReturn($manipulator);


        $this->beConstructedWith(self::CONFIG, $imagine);
        $this->resize(self::IMAGE_PATH)->shouldReturn($savePath);
    }
}
