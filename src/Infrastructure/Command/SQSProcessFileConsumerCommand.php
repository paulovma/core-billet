<?php

namespace App\Infrastructure\Command;

use App\Infrastructure\SQS\File\SQSMessageConsumer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:sqs-process-file-worker',
    description: 'Add a short description for your command',
)]
class SQSProcessFileConsumerCommand extends Command
{

    private SQSMessageConsumer $sQSMessageConsumer;

    public function __construct(SQSMessageConsumer $sQSMessageConsumer)
    {
        $this->sQSMessageConsumer = $sQSMessageConsumer;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->sQSMessageConsumer->consumeMessages();
        // $io = new SymfonyStyle($input, $output);

        // $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        // return Command::SUCCESS;
    }
}
