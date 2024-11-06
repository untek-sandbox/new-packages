<?php

namespace Untek\Database\Seed\Application\Handlers;

use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Untek\Component\Cqrs\Application\Abstract\CqrsHandlerInterface;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;
use Untek\Database\Seed\Application\Validators\GetTablesQueryValidator;

#[AsMessageHandler]
class GetTablesQueryHandler implements CqrsHandlerInterface
{

    public function __construct(
        private Connection $connection,
        private array $excludeTables,
        private GetTablesQueryValidator $commandValidator,
    )
    {
    }

    /**
     * @param \Untek\Database\Seed\Application\Queries\GetTablesQuery $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(\Untek\Database\Seed\Application\Queries\GetTablesQuery $command)
    {
//        $validator = new GetTablesQueryValidator();
        $this->commandValidator->validate($command);

        $tableNames = $this->connection->getSchemaManager()->listTableNames();
        $tableNames = $this->fileterTables($tableNames);
        
        return $tableNames;
    }

    private function fileterTables(array $tableNames): array {
        $filteredTables = [];
        foreach ($tableNames as $tableName) {
            if(!in_array($tableName, $this->excludeTables)) {
                $filteredTables[] = $tableName;
            }
        }
        return $filteredTables;
    }
}