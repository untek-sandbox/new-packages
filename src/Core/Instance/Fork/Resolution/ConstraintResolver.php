<?php

namespace Untek\Core\Instance\Fork\Resolution;

use Untek\Core\Instance\Fork\Argument\ArgumentDescriptions;

class ConstraintResolver
{
    /**
     * @param ArgumentDescriptions $descriptions
     *
     * @return ResolutionConstraintCollection
     */
    public function resolveConstraints(ArgumentDescriptions $descriptions)
    {
        $constraints = [];
        $types = [];
        foreach ($descriptions as $description) {
            if (in_array($description->getType(), $types)) {
                $constraints[] = new ResolutionConstraint(ResolutionConstraint::STRICT_MATCHING, [
                    'type' => $description->getType(),
                ]);
            }

            $types[] = $description->getType();
        }

        return new ResolutionConstraintCollection($constraints);
    }
}
