<?php

namespace Untek\Framework\Telegram\Symfony4\Commands;

use React\EventLoop\Loop;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Lock\Exception\LockAcquiringException;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Framework\Telegram\Domain\Repositories\File\ConfigRepository;
use Untek\Framework\Telegram\Infrastructure\Services\LongPullService;

class LongPullCommand extends Command
{

    use LockableTrait;

    private SymfonyStyle $io;

    public function __construct(
        private LongPullService $longPullService,
        private ConfigRepository $configRepository,
    )
    {
        parent::__construct();
    }

    public static function getDefaultName(): ?string
    {
        return 'telegram:long-pull';
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        // SymfonyStyle is an optional feature that Symfony provides so you can
        // apply a consistent look to the commands of your application.
        // See https://symfony.com/doc/current/console/style.html
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->lock()) {
            $this->io->writeln('The command is already running in another process.');

            return Command::SUCCESS;
        }

        $this->io->writeln('<fg=white># Long pull</>');
        $this->io->writeln('');
        $this->io->writeln('<fg=white>timeout:</> <fg=yellow>' . $this->configRepository->getLongpullTimeout() . ' second</>');

        try {
            $callback = function () {
                $this->runLoopItem();
            };
            $loop = Loop::get();
            $loop->addPeriodicTimer(0, $callback);
            $loop->run();

        } catch (LockAcquiringException $e) {
            $this->io->writeln('<fg=yellow>' . $e->getMessage() . '</>');
            $this->io->writeln('');
        }

        $this->release();

        return Command::SUCCESS;
    }

    protected function runLoopItem(): void
    {
        if (getenv('APP_DEBUG') == '1') {
            $this->io->writeln('<fg=white>wait...</>');
        }
        $updates = $this->longPullService->findAll();
        if ($updates) {
            //$this->io->writeln('<fg=green>has updates</>');
            foreach ($updates as $update) {
//                    dd($update['message']['from']['id']);
                if (!empty($update['message'])) {
                    $line = 'message ' . $update['update_id'] . ' from ' . $update['message']['chat']['id'];
                    if (isset($update['message']['chat']['username'])) {
                        $line .= ' (@' . $update['message']['chat']['username'] . ')';
                    } elseif ($update['message']['chat']['first_name']) {
                        $line .= ' (' . $update['message']['chat']['first_name'] . ')';
                    }
                    $this->io->write('<fg=default> ' . $line . ' ... </>');
                    try {
                        $this->longPullService->runBotFromService($update);
                        $this->io->writeln('<fg=green>OK</>');
                    } catch (\Throwable $e) {
                        $this->longPullService->setHandled($update);
                        $this->io->writeln('<fg=red>FAIL ' . $e->getMessage() . '</>');
                    }
                } elseif (!empty($update['channel_post'])) {
                    $line = 'channel post ' . $update['update_id'] . ' from ' . $update['channel_post']['chat']['id'];
                    if ($update['channel_post']['chat']['title']) {
                        $line .= ' (' . $update['channel_post']['chat']['title'] . ')';
                    }
                    $this->io->write('<fg=default> ' . $line . ' ... </>');
                    $this->io->writeln('<fg=yellow>SKIP</>');
                    $this->longPullService->setHandled($update);
                }
            }
        }
    }
}
