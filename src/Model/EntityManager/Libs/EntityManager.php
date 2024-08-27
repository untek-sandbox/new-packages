<?php

namespace Untek\Model\EntityManager\Libs;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Psr\Container\ContainerInterface;
use Untek\Component\Dev\Helpers\DeprecateHelper;
use Untek\Core\Container\Interfaces\ContainerConfiguratorInterface;
use Untek\Core\Contract\Common\Exceptions\InvalidConfigException;
use Untek\Core\Contract\Common\Exceptions\InvalidMethodParameterException;
use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Model\Entity\Helpers\EntityHelper;
use Untek\Model\EntityManager\Interfaces\EntityManagerConfiguratorInterface;
use Untek\Model\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Model\EntityManager\Interfaces\OrmInterface;
use Untek\Model\Repository\Interfaces\CrudRepositoryInterface;
use Untek\Model\Repository\Interfaces\RepositoryInterface;
use Untek\Model\Validator\Exceptions\UnprocessibleEntityException;
use Untek\Persistence\Contract\Exceptions\AlreadyExistsException;
use Untek\Persistence\Contract\Exceptions\NotFoundException;

class EntityManager implements EntityManagerInterface
{

    private $container;
    private $entityManagerConfigurator;
    private $containerConfigurator;
    private static $instance;

    public function __construct(
        ContainerInterface $container,
        EntityManagerConfiguratorInterface $entityManagerConfigurator,
        ContainerConfiguratorInterface $containerConfigurator = null
    )
    {
        $this->container = $container;
        $this->entityManagerConfigurator = $entityManagerConfigurator;
        $this->containerConfigurator = $containerConfigurator;
    }

    public static function getInstance(ContainerInterface $container = null): self
    {
        if (!isset(self::$instance)) {
            if ($container == null) {
                throw new InvalidMethodParameterException('Need Container for create EntityManager');
            }
            self::$instance = $container->get(self::class);
//            self::$instance = new self($container);
        }
        return self::$instance;
    }

    /**
     * @param string $entityClass
     * @return object
     * @throws InvalidConfigException
     */
    public function getRepository(string $entityClass): object
    {
        /*$repositoryDefition = $this->entityManagerConfigurator->entityToRepository($entityClass);

        if (!$repositoryDefition) {
            $abstract = $this->findInDefinitions($entityClass);
            if ($abstract) {
                $entityClass = $abstract;
            } else {
                throw new InvalidConfigException("Not found \"{$entityClass}\" in entity manager.");
            }
        }*/
        $class = $this->entityManagerConfigurator->entityToRepository($entityClass);
        return $this->getRepositoryByClass($class);
    }

    private function findInDefinitions(string $entityClass)
    {
        DeprecateHelper::softThrow();
        /*$containerConfig = $this->containerConfigurator->getConfig();
        if (empty($containerConfig['definitions'])) {
            return null;
        }
        foreach ($containerConfig['definitions'] as $abstract => $concrete) {
            if ($concrete == $entityClass) {
                return $abstract;
            }
        }
        return null;*/
    }

    public function loadEntityRelations(object $entityOrCollection, array $with): void
    {
        if ($entityOrCollection instanceof Collection) {
            $collection = $entityOrCollection;
        } else {
            $collection = new ArrayCollection([$entityOrCollection]);
        }

        $entityClass = get_class($collection->first());
        $repository = $this->getRepository($entityClass);
        $repository->loadRelations($collection, $with);
    }

    public function remove(object $entity): void
    {
        $entityClass = get_class($entity);
        $repository = $this->getRepository($entityClass);
        if ($entity->getId()) {
            $repository->deleteById($entity->getId());
        } else {
            $uniqueEntity = $repository->findOneByUnique($entity);
            /*if (empty($uniqueEntity)) {
                throw new NotFoundException('Unique entity not found!');
            }*/
            $repository->deleteById($uniqueEntity->getId());
        }
    }

    public function persist(object $entity): void
    {
        $entityClass = get_class($entity);
        $repository = $this->getRepository($entityClass);
        $this->persistViaRepository($entity, $repository);
    }

    public function persistViaRepository(object $entity, object $repository): void
    {
        /*$isUniqueDefined = $entity instanceof UniqueInterface && $entity->unique();

        if ($isUniqueDefined) {
            try {
                $uniqueEntity = $repository->findOneByUnique($entity);
                $entity->setId($uniqueEntity->getId());
            } catch (NotFoundException $e) {
            }
        }*/
        if ($entity->getId() == null) {
            $repository->create($entity);
        } else {
            $repository->update($entity);
        }
    }

    protected function checkUniqueExist(object $entity)
    {
        try {
            $uniqueEntity = $this->findOneByUnique($entity);
            foreach ($entity->unique() as $group) {
                $isMach = true;
                $fields = [];
                foreach ($group as $fieldName) {
                    if (PropertyHelper::getValue($entity, $fieldName) === null || PropertyHelper::getValue($uniqueEntity, $fieldName) != PropertyHelper::getValue($entity, $fieldName)) {
                        $isMach = false;
                        break;
                    } else {
                        $fields[] = $fieldName;
                    }
                }
                if ($isMach) {
                    $message = 'domain.message.entity_already_exist';
                    $alreadyExistsException = new AlreadyExistsException($message);
                    $alreadyExistsException->setEntity($uniqueEntity);
                    $alreadyExistsException->setFields($fields);
                    throw $alreadyExistsException;
                }
            }
        } catch (NotFoundException $e) {
        }
    }

    public function insert(object $entity): void
    {
        /*try {
            $this->checkUniqueExist($entity);
        } catch (AlreadyExistsException $alreadyExistsException) {
            $e = new UnprocessibleEntityException();
            foreach ($alreadyExistsException->getFields() as $fieldName) {
                $e->add($fieldName, $alreadyExistsException->getMessage());
            }
            throw $e;
        }*/

        $entityClass = get_class($entity);
        $repository = $this->getRepository($entityClass);
        $repository->create($entity);
    }

    public function update(object $entity): void
    {
        $entityClass = get_class($entity);
        $repository = $this->getRepository($entityClass);
//        dd($entity);
        $repository->update($entity);
    }

    public function findOneByUnique(object $entity): object
    {
        $entityClass = get_class($entity);
        $repository = $this->getRepository($entityClass);
        return $repository->findOneByUnique($entity);
    }

    protected function getRepositoryByClass(string $class): object
    {
        return $this->container->get($class);
    }

    public function createEntity(string $entityClassName, array $attributes = []): object
    {
        $entityInstance = new $entityClassName();
//        $entityInstance = $this->container->get($entityClassName);
        if ($attributes) {
            PropertyHelper::setAttributes($entityInstance, $attributes);
        }
        return $entityInstance;
    }

    public function createEntityCollection(string $entityClassName, array $items): Collection
    {
        $collection = new ArrayCollection();
        foreach ($items as $item) {
            $entityInstance = $this->createEntity($entityClassName, $item);
            $collection->add($entityInstance);
        }
        return $collection;
    }

    public function beginTransaction()
    {
        foreach ($this->ormList as $orm) {
            $orm->beginTransaction();
        }
    }

    public function rollbackTransaction()
    {
        foreach ($this->ormList as $orm) {
            $orm->rollbackTransaction();
        }
    }

    public function commitTransaction()
    {
        foreach ($this->ormList as $orm) {
            $orm->commitTransaction();
        }
    }

    /** @var array | OrmInterface[] */
    private $ormList = [];

    public function addOrm(OrmInterface $orm)
    {
        $this->ormList[] = $orm;
    }
}
