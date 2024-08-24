<?php

namespace Untek\Utility\Init\Presentation\Libs;

use Symfony\Component\Console\Style\StyleInterface;
use Untek\Utility\Init\Presentation\Cli\Tasks\BaseTask;

class Init
{

    public function __construct(
        private StyleInterface $io,
        private array $profileConfig,
    )
    {
    }

    public function run()
    {
        foreach ($this->profileConfig['tasks'] as $taskInstance) {
            $this->runTask($taskInstance);
        }
    }

    private function runTask(BaseTask $taskInstance): void
    {
        $taskInstance->setParams($this->profileConfig);
        $taskInstance->setIo($this->io);
        $taskInstance->run();
    }
}
