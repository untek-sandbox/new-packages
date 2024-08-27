<?php

namespace Untek\Develop\Package\Domain\Entities;

class PackageEntity
{

    private $id;
    private $name;
    private $group;
    private $directory;
    private $config;

    public function getId()
    {
        if($this->id) {
            return $this->id;
        }
        return $this->group->name . '/' . $this->name;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getGroup(): GroupEntity
    {
        return $this->group;
    }

    public function setGroup(GroupEntity $group): void
    {
        $this->group = $group;
    }

    public function getDirectory()
    {
        $vendorDir = realpath(__DIR__ . '/../../../../../../../../vendor');
        return $vendorDir . DIRECTORY_SEPARATOR . $this->getId();
    }

    public function getGitUrl(): string {
        $provider = $this->getGroup()->providerName;
        if($provider == 'github') {
            $gitUrl = "git@github.com:{$this->getId()}.git";
        }
        return $gitUrl;
    }

    public function getConfig(): ConfigEntity
    {
        return $this->config;
    }

    public function setConfig(ConfigEntity $config): void
    {
        $this->config = $config;
    }
}
