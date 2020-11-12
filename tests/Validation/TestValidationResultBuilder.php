<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\Validation;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPath;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResult;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidityViolation;

class TestValidationResultBuilder implements ValidationResultBuilderInterface
{
    /**
     * @var ValidityViolation[]
     */
    private array $validityViolations = [];

    public function addNullViolation(BreadCrumbPath $breadCrumbPath): self
    {
        return $this->addViolation(1001, 'Unexpected NULL value.', $breadCrumbPath);
    }

    public function addTypeViolation(string $type, BreadCrumbPath $breadCrumbPath): self
    {
        return $this->addViolation(1002, "Type '${type}' expected.", $breadCrumbPath);
    }

    public function addRequiredViolation(BreadCrumbPath $breadCrumbPath): self
    {
        return $this->addViolation(1003, 'It is required.', $breadCrumbPath);
    }

    public function addUnexpectedViolation(BreadCrumbPath $breadCrumbPath): self
    {
        return $this->addViolation(1004, 'It is unexpected.', $breadCrumbPath);
    }

    public function addMinCountViolation(int $min, BreadCrumbPath $breadCrumbPath): self
    {
        return $this->addViolation(1005, "Minimal count ${min} expected.", $breadCrumbPath);
    }

    public function addMaxCountViolation(int $max, BreadCrumbPath $breadCrumbPath): self
    {
        return $this->addViolation(1006, "Maximal count ${max} expected.", $breadCrumbPath);
    }

    public function addUniqueViolation(BreadCrumbPath $breadCrumbPath): self
    {
        return $this->addViolation(1007, 'It has to be unique.', $breadCrumbPath);
    }

    /**
     * @param string[] $choices
     */
    public function addChoicesViolation(array $choices, BreadCrumbPath $breadCrumbPath): self
    {
        $choicesString = \implode('|', $choices);
        return $this->addViolation(1008, "It has to be one of '${choicesString}'.", $breadCrumbPath);
    }

    public function addMinimumViolation(float $minimum, BreadCrumbPath $breadCrumbPath): self
    {
        return $this->addViolation(1009, "It has to be bigger or equal then ${minimum}.", $breadCrumbPath);
    }

    public function addExclusiveMinimumViolation(float $minimum, BreadCrumbPath $breadCrumbPath): self
    {
        return $this->addViolation(1010, "It has to be bigger then ${minimum}.", $breadCrumbPath);
    }

    public function addMaximumViolation(float $maximum, BreadCrumbPath $breadCrumbPath): self
    {
        return $this->addViolation(1011, "It has to be less or equal then ${maximum}.", $breadCrumbPath);
    }

    public function addExclusiveMaximumViolation(float $maximum, BreadCrumbPath $breadCrumbPath): self
    {
        return $this->addViolation(1012, "It has to be less then ${maximum}.", $breadCrumbPath);
    }

    public function addMultipleOfViolation(float $multiplier, BreadCrumbPath $breadCrumbPath): self
    {
        return $this->addViolation(1013, "It has to be divisible by ${multiplier}.", $breadCrumbPath);
    }

    public function addMinLengthViolation(int $minLength, BreadCrumbPath $breadCrumbPath): self
    {
        return $this->addViolation(1014, "Minimal length has to be ${minLength}.", $breadCrumbPath);
    }

    public function addMaxLengthViolation(int $maxLength, BreadCrumbPath $breadCrumbPath): self
    {
        return $this->addViolation(1015, "Maximal length has to be ${maxLength}.", $breadCrumbPath);
    }

    public function addFormatViolation(BreadCrumbPath $breadCrumbPath): self
    {
        return $this->addViolation(1016, 'Invalid format.', $breadCrumbPath);
    }

    public function addPatternViolation(BreadCrumbPath $breadCrumbPath): self
    {
        return $this->addViolation(1017, 'It has to match pattern.', $breadCrumbPath);
    }

    public function mergeResult(ValidationResult $validationResult): self
    {
        $this->validityViolations = \array_merge($this->validityViolations, $validationResult->getViolations());
        return $this;
    }

    public function createResult(): ValidationResult
    {
        return new ValidationResult($this->validityViolations);
    }

    private function addViolation(int $violationCode, string $message, BreadCrumbPath $breadCrumbPath): self
    {
        $this->validityViolations[] = new ValidityViolation($violationCode, $message, $breadCrumbPath);
        return $this;
    }
}
