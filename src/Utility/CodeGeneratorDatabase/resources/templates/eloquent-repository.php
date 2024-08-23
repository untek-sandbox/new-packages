<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $normalizerClassName
 */

use Untek\Utility\CodeGeneratorApplication\Presentation\Enums\PropertyTypeEnum;
use Laminas\Code\Generator\PropertyGenerator;
use Yiisoft\Strings\Inflector;
use Untek\Core\Instance\Helpers\ClassHelper;

?>

namespace <?= $namespace ?>;

use <?= $interfaceClassName ?>;
use <?= $modelClassName ?>;
use <?= $normalizerClassName ?>;
use <?= $relationClassName ?>;
use Untek\Database\Eloquent\Infrastructure\Abstract\AbstractEloquentCrudRepository;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Untek\Component\Relation\Interfaces\RelationConfigInterface;
use Untek\Persistence\Normalizer\DbNormalizerInterface;

class <?= $className ?> extends AbstractEloquentCrudRepository implements <?= $className ?>Interface
{

    public function getTableName(): string
    {
        return '<?= $tableName ?>';
    }

    public function getClassName(): string
    {
        return <?= ClassHelper::getClassOfClassName($modelClassName) ?>::class;
    }

    protected function getNormalizer(): DbNormalizerInterface
    {
        return new <?= ClassHelper::getClassOfClassName($normalizerClassName) ?>();
    }

    public function getRelation(): RelationConfigInterface
    {
        return new <?= ClassHelper::getClassOfClassName($relationClassName) ?>();
    }
}