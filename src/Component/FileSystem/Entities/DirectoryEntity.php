<?php

namespace Untek\Component\FileSystem\Entities;

use Doctrine\Common\Collections\ArrayCollection;

class DirectoryEntity extends BaseEntity
{

    protected $items = null;

    public function getType()
    {
        return self::TYPE_DIRECTORY;
    }

    /**
     * @return null | ArrayCollection | FileEntity[]|DirectoryEntity[]
     */
    public function getItems()
    {
        return $this->items;
    }

    public function setItems($items): void
    {
        $this->items = $items;
    }
}
