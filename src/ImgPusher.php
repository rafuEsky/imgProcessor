<?php

namespace App;

use App\ImgPusherTransport\ImgPusherTransportChain;

class ImgPusher
{
    private ImgPusherTransportChain $transportChain;

    public function __construct(ImgPusherTransportChain $transportChain)
    {
        $this->transportChain = $transportChain;
    }

    /**
     * @param string $imagePath
     * @param string $transportName
     * @return void
     * @throws \Exception
     */
    public function push(string $imagePath, string $transportName): void
    {
        $this->transportChain->getTransport($transportName)->push($imagePath);
    }
}