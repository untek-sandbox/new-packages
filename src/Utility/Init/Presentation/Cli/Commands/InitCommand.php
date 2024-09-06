<?php

namespace Untek\Utility\Init\Presentation\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Untek\Component\Text\Libs\TemplateRender;
use Untek\Framework\Console\Symfony4\Question\ChoiceQuestion;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Persistence\Contract\Exceptions\NotFoundException;
use Untek\Utility\Init\Presentation\Libs\Init;

class InitCommand extends Command
{

    private SymfonyStyle $io;

    public static function getDefaultName(): ?string
    {
        return 'init';
    }

    protected function configure()
    {
        $this->addOption(
            'config',
            null,
            InputOption::VALUE_OPTIONAL,
            '',
            false
        );
        $this->addOption(
            'overwrite',
            null,
            InputOption::VALUE_OPTIONAL,
            '',
            false
        );
        /*$this->addOption(
            'profile',
            null,
            InputOption::VALUE_OPTIONAL,
            '',
            null
        );*/
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->writeln("Application Initialization Tool\n");

        $overwrite = $input->getOption('overwrite');
        $profile = $input->getOption('profile');
        $config = $input->getOption('config');

        $configFile = (new TemplateRender())
            ->addReplacement('ROOT_DIRECTORY', getenv('ROOT_DIRECTORY'))
            ->renderTemplate($config);

        $profiles = $this->loadProfiles($configFile);

        if (empty($profile)) {
            $profile = $this->userInput($profiles);
        }

        $profileConfig = $this->findProfile($profile, $profiles);
        $profileConfig['overwrite'] = $overwrite;

        $initLib = new Init($this->io, $profileConfig);

        $this->io->writeln("\n  Start initialization ...\n\n");
        $initLib->run();
        $this->io->newLine();
        $this->io->success("Initialization completed.");

        return Command::SUCCESS;
    }

    private function findProfile(string $name, array $profiles): array
    {
        $lowerName = mb_strtolower($name);
        foreach ($profiles as $profile) {
            if (mb_strtolower($profile['name']) === $lowerName || mb_strtolower($profile['title']) === $lowerName) {
                return $profile;
            }
        }
        throw new NotFoundException('Profile "' . $name . '" not found.');
    }

    private function loadProfiles(string $configFile): array
    {
        $profiles = require $configFile;
        $newProfiles = [];
        $slugger = new AsciiSlugger();
        foreach ($profiles as $profile) {
            if (empty($profile['title'])) {
                throw new \Exception('Title of profile is empty.');
            }
            $profile['name'] = $slugger->slug($profile['title'])->lower()->toString();
            $newProfiles[$profile['title']] = $profile;
        }
        return $newProfiles;
    }

    private function userInput($profiles)
    {
        $envName = $this->selectEnv($profiles);
        if ($envName === null) {
            $this->io->write("\n  Quit initialization.\n");
            exit(Command::SUCCESS);
        }
        $this->validateEnvName($envName, $profiles);
        $this->userConfirm($envName);
        return $envName;
    }

    private function selectEnv($profiles): ?string
    {
        $envNames = array_keys($profiles);
        $question = new ChoiceQuestion(
            'Which environment do you want the application to be initialized in?',
            $envNames,
            0
        );
        return $this->io->askQuestion($question);
    }

    private function userConfirm(string $envName)
    {
        $questionText = "  Initialize the application under '{$envName}' environment?";
        $answer = $this->io->confirm($questionText, false);
        if (!$answer) {
            $this->io->write("  Quit initialization.\n");
            exit(Command::SUCCESS);
        }
    }

    private function validateEnvName(string $envName, $profiles)
    {
        $envNames = array_keys($profiles);
        if (!in_array($envName, $envNames, true)) {
            $envList = implode(', ', $envNames);
            $this->io->write("\n  $envName is not a valid environment. Try one of the following: $envList. \n");
            exit(Command::INVALID);
        }
    }
}
