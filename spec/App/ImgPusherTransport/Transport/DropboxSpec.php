<?php

namespace spec\App\ImgPusherTransport\Transport;

use App\ImgPusherTransport\ImgPusherTransport;
use App\ImgPusherTransport\Transport\Dropbox;
use PhpSpec\ObjectBehavior;
use Spatie\Dropbox\Client;

class DropboxSpec extends ObjectBehavior
{
    const DIR = '__ANY_DROPBOX_DIR__';
    const TOKEN = '__ANY_TOKEN__';
    const FILENAME = '__ANY_FILENAME__';

    function it_is_initializable(Client $client)
    {
        $this->beConstructedWith(self::TOKEN, self::DIR, $client);
        $client->setAccessToken(self::TOKEN)->willReturn($client);
        $this->shouldHaveType(Dropbox::class);
        $this->shouldImplement(ImgPusherTransport::class);
    }

    function it_push_image(Client $client)
    {
        $this->beConstructedWith(self::TOKEN, self::DIR, $client);
        $client->setAccessToken(self::TOKEN)->willReturn($client);
        $client->upload(self::DIR . 'testFile.jpg', file_get_contents('spec/testFile.jpg'))->willReturn([]);
        $this->push('spec/testFile.jpg');
    }

}
