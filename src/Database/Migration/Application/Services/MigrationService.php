<?php

namespace Untek\Database\Migration\Application\Services;

use Untek\Component\Arr\Helpers\ExtArrayHelper;
use Untek\Database\Migration\Domain\Model\Migration;
use Untek\Database\Migration\Infrastructure\Persistence\Eloquent\Repository\HistoryRepository;
use Untek\Database\Migration\Infrastructure\Persistence\FileSystem\Repository\SourceRepository;
use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Arrays\ArraySorter;

class MigrationService
{

    public function __construct(
        private SourceRepository $sourceRepository,
        private HistoryRepository $historyRepository
    )
    {
    }

    public function upMigration(Migration $migrationEntity)
    {
        $this->historyRepository->upMigration($migrationEntity->className);
    }

    public function downMigration(Migration $migrationEntity)
    {
        $this->historyRepository->downMigration($migrationEntity->className);
    }

    public function allForUp()
    {
        /*
         * читать коллекцию из БД
         * читать коллекцию классов
         * оставить только те классы, которых нет в БД
         * сортировать по возрастанию (version)
         * выпонить up
         */

        $sourceCollection = $this->sourceRepository->getAll();
        $historyCollection = $this->historyRepository->findAll();
        $filteredCollection = $this->historyRepository->filterVersion($sourceCollection, $historyCollection);
        ArraySorter::multisort($filteredCollection, 'version');
        return $filteredCollection;
    }

    public function allForDown()
    {
        /**
         * @var Migration[] $historyCollection
         * @var Migration[] $sourceCollection
         * @var Migration[] $sourceCollectionIndexed
         */

        /*
         * читать коллекцию из БД
         * найди совпадения классов
         * сортировать по убыванию (executed_at)
         * выпонить down
         */

        $historyCollection = $this->historyRepository->findAll();
        $sourceCollection = $this->sourceRepository->getAll();
        $sourceCollectionIndexed = ArrayHelper::index($sourceCollection, 'version');
        foreach ($historyCollection as $migrationEntity) {
            $migrationEntity->className = $sourceCollectionIndexed[$migrationEntity->version]->className;
        }
        ArraySorter::multisort($historyCollection, 'version', SORT_DESC);
        return $historyCollection;
    }
}