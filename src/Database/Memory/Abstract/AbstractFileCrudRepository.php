<?php

namespace Untek\Database\Memory\Abstract;

use Untek\Component\FormatAdapter\StoreFile;

abstract class AbstractFileCrudRepository extends AbstractMemoryCrudRepository
{

    public function __construct(protected string $fileName)
    {
    }

    protected function loadCollection(): void {
        if(empty($this->collection)) {
            $items = $this->getStoreFile()->load();
            if($items) {
                foreach ($items as $item) {
                    $this->collection[] = $this->denormalize($item);
                }
            }
        }
    }

    protected function dumpCollection(): void {
        $itemsRaw = [];
        foreach ($this->collection as $entity) {
            $itemsRaw[] = $this->normalize($entity);
        }
        $this->getStoreFile()->save($itemsRaw);
    }

    protected function getStoreFile(): StoreFile {
        return new StoreFile($this->fileName);
    }
}
