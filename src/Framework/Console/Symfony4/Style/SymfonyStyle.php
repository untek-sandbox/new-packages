<?php

namespace Untek\Framework\Console\Symfony4\Style;

use Doctrine\Common\Collections\Collection;
use Untek\Core\Instance\Helpers\PropertyHelper;
use Untek\Framework\Console\Symfony4\Question\ChoiceQuestion;
use Untek\Model\Entity\Helpers\EntityHelper;
use Untek\Model\Validator\Entities\ValidationErrorEntity;
use Untek\Model\Validator\Exceptions\UnprocessibleEntityException;
use Untek\Model\Validator\Helpers\ValidationHelper;
use Yiisoft\Strings\Inflector;

class SymfonyStyle extends \Symfony\Component\Console\Style\SymfonyStyle
{

    public function clear(): void
    {
        $this->write(sprintf("\033\143"));
    }

    public function inputFormValues(object $reportForm, array $attributes = null)
    {
        do {
            $this->inputFormAttributes($reportForm, $attributes);
            $errorCollection = $this->validateForm($reportForm);
            if ($errorCollection && $errorCollection->count()) {
                $attrs = [];
                foreach ($errorCollection as $errorEntity) {
                    $attrs[] = $errorEntity->getField();
                }
                $attributes = $attrs;
            }
        } while ($errorCollection && $errorCollection->count());
    }

    public function inputFormAttributes(object $reportForm, array $attributes = null)
    {
        if ($attributes == null) {
            $attributes = EntityHelper::getAttributeNames($reportForm);
        }
        foreach ($attributes as $attributeName) {
            $value = PropertyHelper::getValue($reportForm, $attributeName);
//            if (!is_object($value)) {
                $attributeTitle = (new Inflector())->toSentence($attributeName);
//                $value = $this->inputString("Input \"$attributeTitle\"");
                $value = $this->ask($attributeTitle, $value);
                PropertyHelper::setValue($reportForm, $attributeName, $value);
//            }
        }
    }

    public function choiceMulti(string $question, array $choices, $default = null)
    {
        if (null !== $default) {
            $values = array_flip($choices);
            $default = $values[$default] ?? $default;
        }

        $choiceQuestion = new ChoiceQuestion($question, $choices, $default);
        $choiceQuestion->setMultiselect(true);
        return $this->askQuestion($choiceQuestion);
    }

    public function choice(string $question, array $choices, mixed $default = null, bool $multiSelect = false): mixed
    {
        if (null !== $default) {
            $values = array_flip($choices);
            $default = $values[$default] ?? $default;
        }
        $choiceQuestion = new ChoiceQuestion($question, $choices, $default);
        return $this->askQuestion($choiceQuestion);
    }

    /**
     * @param object $reportForm
     * @return array | Collection | ValidationErrorEntity[] | null
     */
    protected function validateForm(object $reportForm): ?Collection
    {
        try {
            ValidationHelper::validateEntity($reportForm);
        } catch (UnprocessibleEntityException $e) {
            $message = '';
            foreach ($e->getErrorCollection() as $errorEntity) {
                $message .= $errorEntity->getField() . ' - ' . $errorEntity->getMessage() . PHP_EOL;
            }
            $message = trim($message);
            $this->warning($message);
            return $e->getErrorCollection();
        }
        return null;
    }
}
