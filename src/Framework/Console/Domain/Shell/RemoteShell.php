<?php

namespace Untek\Framework\Console\Domain\Shell;

use Untek\Framework\Console\Domain\Interfaces\ShellInterface;

class RemoteShell extends LocalShell implements ShellInterface
{

    private ?string $sudoPassword = null;

    public function __construct(private SshConfig $sshConfig)
    {
    }

    public function setSudoPassword(string $sudoPassword): void
    {
        $this->sudoPassword = $sudoPassword;
    }

    public function runCmd(string $cmd, string $path = null): string
    {
        $cmd = $this->prepareCmd($cmd, $path);
        $ssh = $this->prepareSsh();
        $escapedCmd = str_replace('"', '\"', $cmd);
        return $this->runCommandRaw("$ssh \"$escapedCmd\"");
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    private function prepareSsh(): string
    {
        $config = $this->sshConfig;
        $command = '';
        if ($config->getPasswordFile()) {
            $command .= "export SSH_ASKPASS=\"{$config->getPasswordFile()}\" && setsid ";
        } elseif ($config->getPassword()) {
            $command .= "echo \"echo {$config->getPassword()}\" > /tmp/1 && chmod 777 /tmp/1 && ";
            $command .= "export SSH_ASKPASS=\"/tmp/1\" && setsid ";
        }
        $command .= "ssh {$config->getUser()}@{$config->getHost()} -p {$config->getPort()}";
        return $command;
    }
}
