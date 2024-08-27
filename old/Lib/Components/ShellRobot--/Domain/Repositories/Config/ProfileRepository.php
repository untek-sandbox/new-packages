<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Repositories\Config;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Untek\Domain\Query\Entities\Query;
use Untek\Lib\Components\ShellRobot\Domain\Factories\ShellFactory;

class ProfileRepository
{

    public function findOneByName(string $projectName)
    {
//        $collection = $this->findAll();
//        $query = new Query();
//        $query->where('title', $projectName);
//        $profiles = FilterHelper::filterByQuery($profiles, $query);
//        dd($profiles, $projectName);

        $profiles = $this->getItems();
        $profileConfig = $profiles[$projectName];
        return $profileConfig;
    }

    public function findAll(Query $query = null): Collection
    {
        $new = $this->getItems();
        return new ArrayCollection($new);
//        return $new;
    }

    private function getItems(): array
    {
        $profiles = ShellFactory::getConfigProcessor()->get('profiles');
        $new = [];
        foreach ($profiles as $profileName => $profileConfig) {
            if (!is_string($profileName)) {
                $hash = hash('sha256', $profileName);
                $profileName = $profileConfig['name'] ?? $hash;
            }
            $profileConfig['name'] = $profileName;
            $new[$profileName] = $profileConfig;
        }
        return $new;
    }
}
