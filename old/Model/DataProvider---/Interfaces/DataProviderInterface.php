<?php

namespace Untek\Model\DataProvider\Interfaces;

use Doctrine\Common\Collections\Collection;

/**
 * Провайдер данных
 *
 * Используется при выборке коллекции сущностей и параметров пагинации
 */
interface DataProviderInterface
{

    /**
     * Получить коллекцию сущностей
     * @return Collection
     */
    public function getCollection(): Collection;

    /**
     * Получить общее колличество записей в хранилище
     * @return int
     */
    public function getTotalCount(): int;
}