<?php

namespace Untek\Framework\Console\Domain\Shell;

use Untek\Framework\Console\Domain\Base\BaseShellNew;
use Untek\Framework\Console\Domain\Interfaces\ShellInterface;

class Shell extends BaseShellNew //implements ShellInterface
{

    private ?string $sudoPassword = null;

    public function setSudoPassword(string $sudoPassword): void
    {
        $this->sudoPassword = $sudoPassword;
    }

    public function runCmd(string $cmd, string $path = null): string
    {
        $command = $this->prepareCmd($cmd);
        return $this->runCommand($command);
    }

    protected function prepareCmd(string $cmd, string $path = null): string {
        $command = '';
        $path = $path ?: $this->path;
        if ($path) {
            $command .= "cd \"{$path}\" && ";
        }
        $command .= $cmd;
        if($this->sudoPassword) {
            $command = str_replace('sudo', 'echo "' . $this->sudoPassword . '" | sudo -S -k', $command);
        }
        return $command;
    }
}
