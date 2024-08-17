<?php

namespace Untek\Utility\CodeGeneratorDatabase\Application\Validators;

use Symfony\Component\Validator\Constraint;
use Untek\Model\Components\Constraints\Enum;
use Untek\Model\Validator\Libs\AbstractObjectValidator;
use Symfony\Component\Validator\Constraints as Assert;

class GenerateDatabaseCommandValidator extends AbstractObjectValidator
{

    public function getConstraint(): Constraint
    {
        return new Assert\Collection([
            'fields' => [
                'templates' => new Assert\Optional([
                    new Assert\Type('array'),
                ]),
                'namespace' => [
                    new Assert\NotBlank(),
                    new Assert\Length(null, 1, 255),
                    new Assert\Type('string'),
                ],
                'tableName' => [
                    new Assert\NotBlank(),
                    new Assert\Length(null, 1, 255),
                    new Assert\Type('string'),
                ],
                'modelName' => [
                    new Assert\NotBlank(),
                    new Assert\Length(null, 1, 255),
                    new Assert\Type('string'),
                ],
                'properties' => [
                    new Assert\NotBlank(),
                    new Assert\Type('array'),
                ],
                'repositoryDriver' => [
                    new Assert\NotBlank(),
                    new Assert\Type('string'),
                ],
            ]
        ]);
    }
}