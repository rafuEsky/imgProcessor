<?php

namespace App\ImgPusherTransport\Transport;

use App\ImgPusherTransport\ImgPusherTransport;
use Spatie\Dropbox\Client;

class Dropbox implements ImgPusherTransport
{
    private Client $client;
    private $destinationDir;

    public function __construct(string $authorizationToken, $destinationDir, Client $client)
    {
        $client->setAccessToken($authorizationToken);
        $this->client = $client;
        $this->destinationDir = $destinationDir;
    }

    public function push(string $fileName)
    {
        $this->client->upload(
            $this->destinationDir . basename($fileName),
            file_get_contents($fileName)
        );
    }
}