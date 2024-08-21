<?php

namespace Untek\Framework\Console\Domain\Shell;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Untek\Component\Measure\Time\Enums\TimeEnum;
use Untek\Framework\Console\Domain\Interfaces\ShellInterface;

class LocalShell implements ShellInterface
{

    private ?string $sudoPassword = null;

    protected $path = null;
    protected $callback = null;

    public function setCallback(callable $callback = null): void
    {
        $this->callback = $callback;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function runCmd(string $cmd, string $path = null): string
    {
        $shell = new Shell($this->path);
        $cmd = $this->prepareCmd($cmd, $path);
        return $this->runCommandRaw($cmd);
    }

    public function runCommandRaw(string $commandString, ?string $path = null): string
    {
        $process = Process::fromShellCommandline($commandString, $path);
        $process->setTimeout(TimeEnum::SECOND_PER_YEAR);
        $process->run($this->callback);
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $commandOutput = $process->getOutput();
        return $commandOutput;
    }

    protected function prepareCmd(string $cmd, string $path = null): string
    {
        $command = '';
        $path = $path ?: $this->path;
        if ($path) {
            $command .= "cd {$path} && ";
        }
        $command .= $cmd;
        if ($this->sudoPassword) {
            $command = str_replace('sudo', 'echo "' . $this->sudoPassword . '" | sudo -S -k', $command);
        }
        return $command;
    }
}
