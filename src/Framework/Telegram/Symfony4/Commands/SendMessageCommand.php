<?php

namespace Untek\Framework\Telegram\Symfony4\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Framework\Telegram\Infrastructure\Services\BotService;
use Untek\Framework\Telegram\Infrastructure\Services\ResponseService;

class SendMessageCommand extends Command
{

    public function __construct(
        private ResponseService $responseService,
        private BotService $botService,
    )
    {
        parent::__construct();
    }

    public static function getDefaultName(): ?string
    {
        return 'telegram:send-message';
    }

    protected function configure()
    {
        $this
            ->addArgument('chatId', InputArgument::REQUIRED, 'Who do you want to greet?')
            ->addArgument('text', InputArgument::REQUIRED, 'Who do you want to greet?');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $chatId = $input->getArgument('chatId');
        $text = $input->getArgument('text');
        $output->writeln('<fg=white># Send Message</>');
        $this->botService->authByToken(getenv('TELEGRAM_BOT_TOKEN'));
        $this->responseService->sendMessage($chatId, $text);
        return Command::SUCCESS;
    }
}
