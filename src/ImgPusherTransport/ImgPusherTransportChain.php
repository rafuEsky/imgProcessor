<?php

namespace App\ImgPusherTransport;

class ImgPusherTransportChain
{
    private array $transports = [];

    public function addTransport(ImgPusherTransport $transport, $alias): void
    {
        $this->transports[$alias] = $transport;
    }

    /**
     * @param $alias
     * @return ImgPusherTransport|null
     * @throws \Exception
     */
    public function getTransport($alias): ?ImgPusherTransport
    {
        if (array_key_exists($alias, $this->transports)) {
            return $this->transports[$alias];
        }

        throw new \Exception(sprintf('The transport "%s" does not exist.', $alias));
    }

    public function listTransports(): array
    {
        return array_keys($this->transports);
    }
}