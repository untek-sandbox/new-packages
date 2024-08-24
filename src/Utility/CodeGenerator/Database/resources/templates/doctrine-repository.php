<?php

/**
 * @var string $namespace
 * @var string $className
 */

use Untek\Utility\CodeGenerator\Application\Presentation\Enums\PropertyTypeEnum;
use Laminas\Code\Generator\PropertyGenerator;
use Yiisoft\Strings\Inflector;

?>

namespace <?= $namespace ?>;

use <?= $interfaceClassName ?>;
use <?= $modelClassName ?>;
use Untek\Database\Doctrine\Domain\Base\AbstractDoctrineCrudRepository;

class <?= $className ?> extends AbstractDoctrineCrudRepository implements <?= $className ?>Interface
{

    public function getTableName(): string
    {
        return '<?= $tableName ?>';
    }

    public function getClassName(): string
    {
        return <?= (new Inflector())->toPascalCase($tableName) ?>::class;
    }
}