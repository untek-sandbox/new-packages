<?php

namespace Untek\Database\Seed\Application\Handlers;

use Illuminate\Database\Capsule\Manager;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Untek\Component\Cqrs\Application\Abstract\CqrsHandlerInterface;
use Untek\Component\FormatAdapter\StoreFile;
use Untek\Database\Seed\Application\Validators\ExportSeedCommandValidator;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Yiisoft\Arrays\ArrayHelper;

#[AsMessageHandler]
class ExportSeedCommandHandler //implements CqrsHandlerInterface
{

    public function __construct(
        private Manager $manager,
        private string $seedDirectory,
        private ExportSeedCommandValidator $commandValidator,
    )
    {
    }

    /**
     * @param \Untek\Database\Seed\Application\Commands\ExportSeedCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(\Untek\Database\Seed\Application\Commands\ExportSeedCommand $command)
    {
//        $validator = new ExportSeedCommandValidator();
        $this->commandValidator->validate($command);

        $cb = $command->getProgressCallback();
        foreach ($command->getTables() as $table) {

            $connection = $this->manager->getConnection();
            $qb = $connection->table($table);
            $data = $qb->select('*')->get()->toArray();
            $data = ArrayHelper::toArray($data);

            $filePath = $this->seedDirectory . '/' . $table . '.php';
            (new StoreFile($filePath))->save($data);

            $cb($table);
        }
    }
}