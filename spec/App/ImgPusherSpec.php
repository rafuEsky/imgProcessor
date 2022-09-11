<?php

namespace spec\App;

use App\ImgPusher;
use App\ImgPusherTransport\ImgPusherTransport;
use App\ImgPusherTransport\ImgPusherTransportChain;

use PhpSpec\ObjectBehavior;

class ImgPusherSpec extends ObjectBehavior
{
    const TRANSPORT_NAME = '__ANY_TRANSPORT__';
    const IMAGE_PATH = '__ANY_PATH__';

    function it_is_initializable(ImgPusherTransportChain $transportChain)
    {
        $this->beConstructedWith($transportChain);
        $this->shouldHaveType(ImgPusher::class);
    }

    function it_push_file(ImgPusherTransportChain $transportChain, ImgPusherTransport $transport)
    {
        $this->beConstructedWith($transportChain);
        $transport->push(self::IMAGE_PATH)->shouldBeCalled();
        $transportChain->getTransport(self::TRANSPORT_NAME)->willReturn($transport);
        $this->push(self::IMAGE_PATH, self::TRANSPORT_NAME);
    }
}
