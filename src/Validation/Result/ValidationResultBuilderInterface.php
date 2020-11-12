<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Result;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPath;

interface ValidationResultBuilderInterface
{
    public function addNullViolation(BreadCrumbPath $breadCrumbPath): self;

    public function addTypeViolation(string $type, BreadCrumbPath $breadCrumbPath): self;

    public function addRequiredViolation(BreadCrumbPath $breadCrumbPath): self;

    public function addUnexpectedViolation(BreadCrumbPath $breadCrumbPath): self;

    public function addMinCountViolation(int $min, BreadCrumbPath $breadCrumbPath): self;

    public function addMaxCountViolation(int $max, BreadCrumbPath $breadCrumbPath): self;

    public function addUniqueViolation(BreadCrumbPath $breadCrumbPath): self;

    /**
     * @param string[] $choices
     */
    public function addChoicesViolation(array $choices, BreadCrumbPath $breadCrumbPath): self;

    public function addMinimumViolation(float $minimum, BreadCrumbPath $breadCrumbPath): self;

    public function addExclusiveMinimumViolation(float $minimum, BreadCrumbPath $breadCrumbPath): self;

    public function addMaximumViolation(float $maximum, BreadCrumbPath $breadCrumbPath): self;

    public function addExclusiveMaximumViolation(float $maximum, BreadCrumbPath $breadCrumbPath): self;

    public function addMultipleOfViolation(float $multiplier, BreadCrumbPath $breadCrumbPath): self;

    public function addMinLengthViolation(int $minLength, BreadCrumbPath $breadCrumbPath): self;

    public function addMaxLengthViolation(int $maxLength, BreadCrumbPath $breadCrumbPath): self;

    public function addFormatViolation(string $format, BreadCrumbPath $breadCrumbPath): self;

    public function addPatternViolation(string $pattern, BreadCrumbPath $breadCrumbPath): self;

    public function mergeResult(ValidationResult $validationResult): self;

    /**
     * @param ValidityViolation[] $violations
     */
    public function mergeViolations(array $violations): self;

    public function createResult(): ValidationResult;
}
