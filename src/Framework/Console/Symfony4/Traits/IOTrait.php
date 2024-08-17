<?php

namespace Untek\Framework\Console\Symfony4\Traits;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;

trait IOTrait
{

    private $input;
    private $output;
    private $style;

    protected function setInputOutput(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;

        $this->style = new SymfonyStyle($input, $output);
    }

    public function getInput(): InputInterface
    {
        return $this->input;
    }

    public function getOutput(): OutputInterface
    {
        return $this->output;
    }

    public function getStyle(): SymfonyStyle
    {
        return $this->style;
    }
}
