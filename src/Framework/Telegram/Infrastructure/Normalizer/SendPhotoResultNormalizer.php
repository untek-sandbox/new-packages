<?php

namespace Untek\Framework\Telegram\Infrastructure\Normalizer;

use ArrayObject;
use Untek\Core\Contract\Common\Exceptions\NotImplementedMethodException;
use Untek\Core\Instance\Helpers\MappingHelper;
use Untek\Persistence\Normalizer\DatabaseItemNormalizer;
use Untek\Framework\Telegram\Domain\Dto\Photo;
use Untek\Framework\Telegram\Domain\Dto\SendPhotoResult;

class SendPhotoResultNormalizer extends DatabaseItemNormalizer
{

    public function normalize(object $entity): array
    {
        throw new NotImplementedMethodException();
    }

    public function denormalize(array $item, string $type): object
    {
        $photo = $item['photo'];
        unset($item['photo']);
        unset($item['caption_entities']);
        $photoObjects = [];
        foreach ($photo as $photoItem) {
            $photoObjects[] = MappingHelper::restoreObject($photoItem, Photo::class);
        }
        /** @var SendPhotoResult $dto */
        $dto = MappingHelper::restoreObject($item, SendPhotoResult::class);
        $dto->setPhoto($photoObjects);
        return $dto;
    }
}