<?php

namespace App\Command;

use App\Event\ResizeAndPushImageEvent;
use App\ImgPusherTransport\ImgPusherTransportChain;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:img-proc',
    description: 'Resize image and push resized file to specified destination',
    aliases: ['ip']
)]
class ImgProcessorCommand extends Command
{
    private MessageBusInterface $bus;
    private ImgPusherTransportChain $imgPusherTransportChain;

    public function __construct(MessageBusInterface $bus, ImgPusherTransportChain $imgPusherTransportChain)
    {
        $this->bus = $bus;
        $this->imgPusherTransportChain = $imgPusherTransportChain;

        parent::__construct();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sourceFileName = $input->getArgument('sourceFileName');
        $transportName = $input->getArgument('transportName');

        $this->bus->dispatch(new ResizeAndPushImageEvent(
            $sourceFileName,
            $transportName
        ));

        $output->writeln('<info>Succesfull resize file '. $sourceFileName . ' and upload to transport \''. $transportName . '\'</info>');

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'sourceFileName',
                InputArgument::REQUIRED,
                'Path to source filename'
            )
            ->addArgument(
                'transportName',
                InputArgument::REQUIRED,
                'Transport name. Available at config: ' . implode(', ', $this->imgPusherTransportChain->listTransports())
            );
    }
}