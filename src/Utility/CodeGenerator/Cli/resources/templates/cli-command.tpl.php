<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $commandClassName
 * @var string $commandFullClassName
 * @var string $cliCommandName
 * @var array $properties
 */

?>

namespace <?= $namespace ?>;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Untek\Framework\Console\Infrastructure\Validators\NotBlankValidator;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Component\Cqrs\Application\Services\CommandBusInterface;
use <?= $commandFullClassName ?>;

class <?= $className ?> extends Command
{

    private SymfonyStyle $io;

    public function __construct(
        private CommandBusInterface $bus
    ) {
        parent::__construct();
    }

    public static function getDefaultName(): string
    {
        return '<?= $cliCommandName ?>';
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $command = new <?= $commandClassName ?>();
<?php foreach ($properties as $attribute):
    $propertyName = $attribute->getName();
    $propertyType = $attribute->getType()->generate();
    ?>

        $<?= $propertyName ?> = $this->io->ask('Enter <?= $propertyName ?>', null, function ($value) {
            NotBlankValidator::validate($value);
            return $value;
        });
        $command->set<?= ucfirst($propertyName) ?>($<?= $propertyName ?>);
<?php endforeach; ?>

        $result = $this->bus->handle($command);

        $this->io->success('Success result!');

        return Command::SUCCESS;
    }
}