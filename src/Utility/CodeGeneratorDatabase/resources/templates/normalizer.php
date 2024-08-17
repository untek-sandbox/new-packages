<?php

/**
 * @var string $namespace
 * @var string $className
 */

?>

namespace <?= $namespace ?>;

use Untek\Database\Base\Hydrator\DatabaseItemNormalizer;
use ArrayObject;

class <?= $className ?> extends DatabaseItemNormalizer
{

    public function normalize(object $object): array
    {
        $data = parent::normalize($object);
<?php foreach ($properties as $attribute){
    $propertyName = $attribute['name'];
    $fieldName = \Untek\Core\Text\Helpers\Inflector::underscore($propertyName);
    $propertyType = $attribute['type'];
    if($propertyType == 'array') {
        echo "\t\t\$data['$fieldName'] = json_encode(\$data['$fieldName'], JSON_UNESCAPED_UNICODE);\n";
    }
}?>
        return $data;
    }

    public function denormalize(array $data, string $type): object
    {
<?php foreach ($properties as $attribute){
    $propertyName = $attribute['name'];
    $fieldName = \Untek\Core\Text\Helpers\Inflector::underscore($propertyName);
    $propertyType = $attribute['type'];
    if($propertyType == 'array') {
        echo "\t\t\$data['$fieldName'] = json_decode(\$data['$fieldName'], true);\n";
    }
}?>
        return parent::denormalize($data, $type);
    }
}
