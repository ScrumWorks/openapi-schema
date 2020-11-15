<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation;

interface ValidityViolationFactoryInterface
{
    public function createNullableViolation(BreadCrumbPathInterface $breadCrumbPath): ValidityViolationInterface;

    public function createTypeViolation(
        string $type,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface;

    public function createRequiredViolation(BreadCrumbPathInterface $breadCrumbPath): ValidityViolationInterface;

    public function createUnexpectedViolation(BreadCrumbPathInterface $breadCrumbPath): ValidityViolationInterface;

    public function createMinItemsViolation(
        int $min,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface;

    public function createMaxItemsViolation(
        int $max,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface;

    public function createUniqueViolation(BreadCrumbPathInterface $breadCrumbPath): ValidityViolationInterface;

    /**
     * @param string[] $choices
     */
    public function createEnumViolation(
        array $choices,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface;

    public function createMinimumViolation(
        float $minimum,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface;

    public function createExclusiveMinimumViolation(
        float $minimum,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface;

    public function createMaximumViolation(
        float $maximum,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface;

    public function createExclusiveMaximumViolation(
        float $maximum,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface;

    public function createMultipleOfViolation(
        float $multiplier,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface;

    public function createMinLengthViolation(
        int $minLength,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface;

    public function createMaxLengthViolation(
        int $maxLength,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface;

    public function createFormatViolation(
        string $format,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface;

    public function createPatternViolation(
        string $pattern,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface;
}
