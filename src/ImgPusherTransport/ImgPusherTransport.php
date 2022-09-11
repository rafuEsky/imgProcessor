<?php

namespace App\ImgPusherTransport;

interface ImgPusherTransport
{
    public function push(string $fileName);
}