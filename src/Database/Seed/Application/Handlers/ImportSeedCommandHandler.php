<?php

namespace Untek\Database\Seed\Application\Handlers;

use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Untek\Component\FormatAdapter\StoreFile;
use Untek\Component\FileSystem\Helpers\FileHelper;
use Untek\Component\FileSystem\Helpers\FilePathHelper;
use Untek\Database\Base\Domain\Libs\Dependency;
use Untek\Database\Seed\Application\Commands\ImportSeedCommand;
use Untek\Database\Seed\Application\Validators\ImportSeedCommandValidator;
use Untek\Model\Validator\Exceptions\UnprocessableEntityException;

#[AsMessageHandler]
class ImportSeedCommandHandler
{

    public function __construct(
        private Dependency $dependency,
        private Connection $connection,
        private string $seedDirectory,
        private ImportSeedCommandValidator $commandValidator,
    )
    {
    }

    /**
     * @param ImportSeedCommand $command
     * @throws UnprocessableEntityException
     */
    public function __invoke(ImportSeedCommand $command)
    {
//        $validator = new ImportSeedCommandValidator();
        $this->commandValidator->validate($command);

        $tables = $command->getTables();

        $seeds = FileHelper::findFiles($this->seedDirectory);
        $seedList = [];
        foreach ($seeds as $seedFile) {
            $seedName = FilePathHelper::fileNameOnly($seedFile);
            $seedList[$seedName] = $seedFile;
        }

        $sortedTables = $this->dependency->run($tables);



        foreach ($sortedTables as $seedName) {
            if (isset($seedList[$seedName])) {
                $seedFile = $seedList[$seedName];
                $this->import($seedName, $seedFile, $command->getProgressCallback());
            }
        }



        /*$this->connection->beginTransaction();
        try {
            $this->connection->query('SET FOREIGN_KEY_CHECKS=0');
            
            $this->connection->query('SET FOREIGN_KEY_CHECKS=1');
            $this->connection->commit();
        } catch (\Exception $e) {
            $this->connection->rollback();
        }*/

    }

    private function import(string $tableName, string $seedFile, $cb)
    {
        $store = new StoreFile($seedFile);
        $data = $store->load();

        $this->truncate($tableName);

        if(!empty($data)) {
            $this->insert($tableName, $data);
        }
        $this->resetAutoIncrement($tableName);

        /*foreach ($data as $row) {
            $this->connection->insert($tableName, $row);
        }*/

        if ($cb) {
            call_user_func($cb, $tableName . ' (' . count($data) . ')');
        }
    }

    private function truncate(string $tableName)
    {
        $this->connection->query("DELETE FROM {$tableName}");
        $this->resetAutoIncrement($tableName);
    }

    protected function hasAutoincrement(string $tableName): bool
    {
        $columns = $this->connection->getSchemaManager()->listTableColumns($tableName);
        foreach ($columns as $column) {
            if ($column->getAutoincrement()) {
                return true;
            }
        }
        return false;
    }

    protected function resetAutoIncrement(string $tableName)
    {
        $hasAutoincrement = $this->hasAutoincrement($tableName);
        if (!$hasAutoincrement) {
            return;
        }
        $queryBuilder = $this->connection->createQueryBuilder();
        $driverClass = get_class($this->connection->getDriver());
        if ($driverClass == 'Doctrine\DBAL\Driver\PDO\SQLite\Driver') {
            $this->connection->query("DELETE FROM SQLITE_SEQUENCE WHERE name='$tableName';");
        } elseif ($driverClass == 'Doctrine\DBAL\Driver\PDO\PgSQL\Driver') {
            $maxResult = $this->connection->fetchAllAssociative("SELECT MAX(id) FROM $tableName");
            $max = $maxResult[0]['max'] ?? null;
//            $max = $queryBuilder->max('id');
            if ($max) {
                $pkName = 'id';
                $sql = 'SELECT setval(\'' . $tableName . '_' . $pkName . '_seq\', ' . ($max) . ')';
                $connection = $queryBuilder->getConnection();
                $connection->executeQuery($sql);
            }
        }
    }

    public function insert(string $table, array $list)
    {
        if (empty($list)) {
            return $this->connection->executeStatement('INSERT INTO ' . $table . ' () VALUES ()');
        }

        $columnSql = $this->generateColumnSql($list);
        $valuesSql = $this->generateValuesSql($list);

        $sql =
            'INSERT INTO ' . $table . $columnSql .
            ' VALUES ' . $valuesSql;

        return $this->connection->executeStatement($sql);
    }

    private function generateColumnSql(array $list): string
    {
        $columns = [];
        foreach ($list[0] as $columnName => $value) {
            $columns[] = $columnName;
        }
        return ' (' . implode(', ', $columns) . ')';
    }

    private function generateValuesSql(array $list): string
    {
        $valuesList = [];
        foreach ($list as $row) {
            $quotedValues = [];
            foreach ($row as $columnName => $value) {
                if (!is_null($value)) {
                    $quotedValues[] = $this->connection->quote($value);
                } else {
                    $quotedValues[] = 'null';
                }
            }
            $valuesList[] = '(' . implode(', ', $quotedValues) . ')';
        }
        return implode(', ', $valuesList);
    }
}