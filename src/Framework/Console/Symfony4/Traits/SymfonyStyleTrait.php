<?php

namespace Untek\Framework\Console\Symfony4\Traits;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;

trait SymfonyStyleTrait
{

    private SymfonyStyle $io;

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        // SymfonyStyle is an optional feature that Symfony provides so you can
        // apply a consistent look to the commands of your application.
        // See https://symfony.com/doc/current/console/style.html
        $this->io = new SymfonyStyle($input, $output);
    }
}
