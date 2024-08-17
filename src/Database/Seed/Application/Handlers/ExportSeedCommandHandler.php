<?php

namespace Untek\Database\Seed\Application\Handlers;

use Untek\Component\FormatAdapter\StoreFile;
use Untek\Core\Arr\Helpers\ArrayHelper;
use Untek\Database\Eloquent\Domain\Capsule\Manager;
use Untek\Model\Cqrs\Application\Abstract\CqrsHandlerInterface;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;

class ExportSeedCommandHandler implements CqrsHandlerInterface
{

    public function __construct(
        private Manager $manager,
        private string $seedDirectory,
    )
    {
    }

    /**
     * @param \Untek\Database\Seed\Application\Commands\ExportSeedCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(\Untek\Database\Seed\Application\Commands\ExportSeedCommand $command)
    {
        $validator = new \Untek\Database\Seed\Application\Validators\ExportSeedCommandValidator();
        $validator->validate($command);

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