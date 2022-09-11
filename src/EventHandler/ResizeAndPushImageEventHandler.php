<?php

namespace App\EventHandler;

use App\Event\ResizeAndPushImageEvent;
use App\ImgPusher;
use App\ImgResizer;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ResizeAndPushImageEventHandler
{
    private ImgPusher $imgPusher;
    private ImgResizer $imgResizer;

    public function __construct(
        ImgResizer $imgResizer,
        ImgPusher  $imgPusher
    ) {
        $this->imgPusher = $imgPusher;
        $this->imgResizer = $imgResizer;
    }

    public function __invoke(ResizeAndPushImageEvent $pushImageEvent): void
    {
        $resizedImagePath = $this->imgResizer->resize($pushImageEvent->getSourceFileName());
        $this->imgPusher->push($resizedImagePath, $pushImageEvent->getTransportName());
    }
}