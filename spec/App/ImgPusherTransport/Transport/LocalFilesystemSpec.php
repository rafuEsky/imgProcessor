<?php

namespace spec\App\ImgPusherTransport\Transport;

use App\ImgPusherTransport\Transport\LocalFilesystem;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem;

class LocalFilesystemSpec extends ObjectBehavior
{
    const DIR = '__ANY_LOCAL_DIR__';
    const FILENAME = '/path/__ANY_FILENAME__';

    function it_is_initializable(Filesystem $filesystem)
    {
        $this->beConstructedWith(self::DIR, $filesystem);
        $this->shouldHaveType(LocalFilesystem::class);
    }

    function it_push_file(Filesystem $filesystem)
    {
        $this->beConstructedWith(self::DIR, $filesystem);
        $filesystem->copy(
            self::FILENAME,
            self::DIR . DIRECTORY_SEPARATOR . basename(self::FILENAME)
        )->shouldBeCalled();
        $this->push(self::FILENAME);
    }
}
