<?php

namespace Untek\Component\Relation\Traits;

use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\Attribute\Required;
use Untek\Component\Relation\Interfaces\RelationConfigInterface;
use Untek\Component\Relation\Interfaces\RelationInterface;
use Untek\Component\Relation\Libs\RelationConfigurator;
use Untek\Component\Relation\Libs\RelationLoader;
use Untek\Core\Contract\Common\Exceptions\NotImplementedMethodException;

trait RepositoryRelationTrait
{
    
    protected ?ContainerInterface $container = null;
    private RelationLoader $relationLoader;

    #[Required]
    public function setContainer(ContainerInterface $container): ?ContainerInterface
    {
        $this->container = $container;
        $this->relationLoader = new RelationLoader($this->container);
        return $container;
    }
    
    public function getRelation(): RelationConfigInterface
    {
        throw new NotImplementedMethodException('Need relation class.');
    }

    public function relations(): RelationConfigurator
    {
        $configurator = new RelationConfigurator();
        $this->getRelation()->relations($configurator);
        return $configurator;
    }

    protected function loadRelations(array $collection, array $with)
    {
        $relations = $this->relations();
        if ($relations->isEmpty()) {
            return;
        }
//        $relationLoader = new RelationLoader($this->container);
        $this->relationLoader->load($this, $collection, $with);
    }
}
