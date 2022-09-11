<?php

namespace App\ImgPusherTransport\Transport;

use App\ImgPusherTransport\ImgPusherTransport;
use Symfony\Component\Filesystem\Filesystem;

class LocalFilesystem implements ImgPusherTransport
{
    private Filesystem $filesystem;
    private string $destinationDir;

    public function __construct(string $destinationDir, Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->destinationDir = $destinationDir;
    }

    public function push(string $fileName)
    {
        $this->filesystem->copy(
            $fileName,
            $this->destinationDir . DIRECTORY_SEPARATOR . basename($fileName)
        );
    }
}