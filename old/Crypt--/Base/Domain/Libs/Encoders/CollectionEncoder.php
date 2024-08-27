<?php

namespace Untek\Crypt\Base\Domain\Libs\Encoders;

use Psr\Container\ContainerInterface;
use Untek\Component\Dev\Helpers\DeprecateHelper;
use Untek\Core\Container\Traits\ContainerAwareTrait;
use Untek\Core\Instance\Helpers\InstanceHelper;
use Untek\Core\Contract\Encoder\Interfaces\DecodeInterface;
use Untek\Core\Contract\Encoder\Interfaces\EncodeInterface;
use Untek\Core\Collection\Interfaces\Enumerable;
use Untek\Model\Entity\Helpers\EntityHelper;

DeprecateHelper::hardThrow();

class CollectionEncoder implements EncoderInterface
{

    private $encoderCollection;

    public function __construct(Enumerable $encoderCollection, private ?ContainerInterface $container = null)
    {
        $this->encoderCollection = $encoderCollection;
    }

    public function encode($data)
    {
        $data = EntityHelper::toArray($data);
        $encoders = $this->encoderCollection->toArray();
        foreach ($encoders as $encoderClass) {
            /** @var EncodeInterface $encoderInstance */
//            (new InstanceResolver($this->ensureContainer()))->ensure();
            $encoderInstance = InstanceHelper::ensure($encoderClass, [], $this->ensureContainer());
            $data = $encoderInstance->encode($data);
        }
        return $data;
    }

    public function decode($data)
    {
        $encoders = $this->encoderCollection->toArray();
        $encoders = array_reverse($encoders);
        foreach ($encoders as $encoderClass) {
            /** @var DecodeInterface $encoderInstance */
            $encoderInstance = InstanceHelper::ensure($encoderClass, [], $this->ensureContainer());
            $data = $encoderInstance->decode($data);
        }
        return $data;
    }
}
