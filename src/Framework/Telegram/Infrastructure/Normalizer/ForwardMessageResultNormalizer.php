<?php

namespace Untek\Framework\Telegram\Infrastructure\Normalizer;

use ArrayObject;
use Untek\Component\Arr\Helpers\ArrayHelper;
use Untek\Core\Contract\Common\Exceptions\NotImplementedMethodException;
use Untek\Core\Instance\Helpers\MappingHelper;
use Untek\Persistence\Normalizer\DatabaseItemNormalizer;
use Untek\Framework\Telegram\Domain\Dto\ForwardMessageResult;

class ForwardMessageResultNormalizer extends DatabaseItemNormalizer
{

    public function normalize(object $entity): array
    {
        throw new NotImplementedMethodException();
    }

    public function denormalize(array $item, string $type): object
    {
        $item = ArrayHelper::extractByKeys($item, [
            'message_id',
            'text',
            'date',
//            'edit_date',
            'from',
            'chat',
            'forward_from',
            'forward_date',
        ]);
        /** @var ForwardMessageResult $dto */
        $dto = MappingHelper::restoreObject($item, ForwardMessageResult::class);
        return $dto;
    }
}