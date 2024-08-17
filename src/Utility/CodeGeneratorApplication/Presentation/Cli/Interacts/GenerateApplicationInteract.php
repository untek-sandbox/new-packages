<?php


namespace Untek\Utility\CodeGeneratorApplication\Presentation\Cli\Interacts;

use Symfony\Component\Console\Question\Question;
use Untek\Core\Text\Helpers\Inflector;
use Untek\Framework\Console\Infrastructure\Validators\ClassNameValidator;
use Untek\Framework\Console\Infrastructure\Validators\NotBlankValidator;
use Untek\Framework\Console\Infrastructure\Validators\PropertyNameValidator;
use Untek\Framework\Console\Symfony4\Question\ChoiceQuestion;
use Untek\Framework\Console\Symfony4\Style\SymfonyStyle;
use Untek\Utility\CodeGenerator\Application\Interfaces\InteractInterface;
use Untek\Utility\CodeGeneratorApplication\Application\Commands\GenerateApplicationCommand;
use Untek\Utility\CodeGeneratorApplication\Application\Enums\TypeEnum;
use Untek\Utility\CodeGeneratorApplication\Presentation\Enums\PropertyTypeEnum;

class GenerateApplicationInteract implements InteractInterface
{

    public function input(SymfonyStyle $io): array
    {
        $namespace = $this->inputNamespace($io);
        $type = $this->inputType($io);
        $name = $io->ask('Enter a action name', null, [ClassNameValidator::class, 'validate']);
        $properties = $this->inputProperties($io);
        $command = new GenerateApplicationCommand();
        $command->setCommandType($type);
        $command->setNamespace($namespace);
        $command->setCommandName($name);
        $command->setProperties($properties);

        return [$command];
    }

    private function inputNamespace(SymfonyStyle $io): string
    {
        $namespace = $io->ask('Enter a namespace', null, function ($value): ?string {
            NotBlankValidator::validate($value);
            ClassNameValidator::validate($value);
            return $value;
        });
        return $namespace;
    }

    private function inputType(SymfonyStyle $io): string
    {
        $question = new ChoiceQuestion(
            'Select action type',
            [
                TypeEnum::COMMAND,
                TypeEnum::QUERY,
            ]
        );
        return $io->askQuestion($question);
    }

    private function inputProperties(SymfonyStyle $io): array
    {
        $properties = [];
        do {
            $propertyName = $io->ask('Enter a property name or press Enter for skip', null, function ($value): ?string {
                if ($value) {
                    PropertyNameValidator::validate($value);
                }
                return $value;
            });
            if ($propertyName) {
                $propertyType = $this->inputPropertyType($io, $propertyName);
                $properties[] = [
                    'name' => $propertyName,
                    'type' => $propertyType,
                ];
            }
        } while ($propertyName != null);
        return $properties;
    }

    private function inputPropertyType(SymfonyStyle $io, string $propertyName): string
    {
        $allValidTypes = PropertyTypeEnum::getList();
        do {
            $defaultType = $this->getDefaultTypeByPropertyName($propertyName);
            $question = new Question('Field type (enter <comment>?</comment> to see all types)', $defaultType);
            $question->setAutocompleterValues($allValidTypes);
            $propertyType = $io->askQuestion($question);
            if ('?' === $propertyType) {
                $io->listing($allValidTypes);
                $io->writeln('');
                $propertyType = null;
            } elseif (!in_array($propertyType, $allValidTypes)) {
                $io->listing($allValidTypes);
                $io->error(sprintf('Invalid type "%s".', $propertyType));
                $io->writeln('');
                $propertyType = null;
            }
        } while (null === $propertyType);
        return $propertyType;
    }

    private function getDefaultTypeByPropertyName(string $propertyName): ?string
    {
        $defaultType = null;
        $snakeCasedPropertyName = Inflector::underscore($propertyName);

        if (str_ends_with($snakeCasedPropertyName, '_at')) {
            $defaultType = PropertyTypeEnum::DATE_TIME;
        } elseif (str_ends_with($snakeCasedPropertyName, '_id') || str_ends_with($snakeCasedPropertyName, '_by') || str_ends_with($snakeCasedPropertyName, '_count') || str_ends_with($snakeCasedPropertyName, '_size') || 'id' === $snakeCasedPropertyName) {
            $defaultType = PropertyTypeEnum::INTEGER;
        } elseif (str_starts_with($snakeCasedPropertyName, 'is_') || str_starts_with($snakeCasedPropertyName, 'has_')) {
            $defaultType = PropertyTypeEnum::BOOL;
        } elseif (str_ends_with($snakeCasedPropertyName, '_name') || 'name' == $snakeCasedPropertyName || 'title' == $snakeCasedPropertyName || 'text' == $snakeCasedPropertyName || 'description' == $snakeCasedPropertyName || 'content' == $snakeCasedPropertyName) {
            $defaultType = PropertyTypeEnum::STRING;

//        } elseif ('uuid' === $snakeCasedPropertyName) {
//            $defaultType = Type::hasType('uuid') ? 'uuid' : 'guid';
//        } elseif ('guid' === $snakeCasedPropertyName) {
//            $defaultType = 'guid';
        }
        return $defaultType;
    }
}