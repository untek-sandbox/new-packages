<?php

namespace Untek\Framework\Telegram\Symfony4\Commands;

use Untek\Core\Container\Libs\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Core\Container\Helpers\ContainerHelper;
use Untek\Framework\Telegram\Infrastructure\Services\BotService;
use Untek\Framework\Telegram\Infrastructure\Services\ResponseService;

class SendMessageCommand extends Command
{

    protected static $defaultName = 'telegram:send-message';

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
        dd($chatId, $text);
        $output->writeln('<fg=white># Send Message</>');
        $container = ContainerHelper::getContainer();
        /** @var ResponseService $responseService */
        $responseService = $container->get(ResponseService::class);
        /** @var BotService $botService */
        $botService = $container->get(BotService::class);
//        $config = include __DIR__ . '/../../../config/main.php';
//        $botService->authByToken($config['telegram']['bot']['token']);
        $botService->authByToken(getenv('TELEGRAM_BOT_TOKEN'));
        $responseService->sendMessage($chatId, $text);
        return Command::SUCCESS;
    }
}
