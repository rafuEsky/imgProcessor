<?php

namespace App\DependencyInjection\Compiler;

use App\ImgPusherTransport\ImgPusherTransportChain;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ImgPusherTransportPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(ImgPusherTransportChain::class)) {
            return;
        }

        $definition = $container->findDefinition(ImgPusherTransportChain::class);
        $taggedServices = $container->findTaggedServiceIds('app.img_pusher_transport');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('addTransport', [
                    new Reference($id),
                    $attributes['alias'],
                ]);
            }
        }
    }
}