<?php

namespace spec\App\ImgPusherTransport;

use App\ImgPusherTransport\ImgPusherTransport;
use App\ImgPusherTransport\ImgPusherTransportChain;
use PhpSpec\ObjectBehavior;

class ImgPusherTransportChainSpec extends ObjectBehavior
{
    const ALIAS = '__ANY_ALIAS__';
    const ALIAS2 = '__ANY_ALIAS2__';

    function it_is_initializable()
    {
        $this->shouldHaveType(ImgPusherTransportChain::class);
    }

    function it_adds_and_gets_transport(ImgPusherTransport $transport)
    {
        $this->addTransport($transport, self::ALIAS);
        $this->getTransport(self::ALIAS)->shouldReturn($transport);
    }

    function it_list_transports_keys(ImgPusherTransport $transport)
    {
        $this->addTransport($transport, self::ALIAS);
        $this->addTransport($transport, self::ALIAS2);
        $this->listTransports()->shouldReturn([self::ALIAS, self::ALIAS2]);
    }

    function it_should_throw_exception_on_not_existing_transport()
    {
        $this->shouldThrow('\Exception')->during('getTransport' , ['NOT_EXISTING_TRANSPORT_ALIAS']);
    }
}
