<?php

namespace Untek\Framework\Console\Domain\Interfaces;

use Untek\Framework\Console\Domain\Base\BaseShellNew;

interface ShellInterface
{

    public function setCallback(callable $callback = null): void;

    public function runCmd(string $cmd, string $path = null): string;
}
