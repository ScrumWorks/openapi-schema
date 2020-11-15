<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Result;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;
use ScrumWorks\OpenApiSchema\Validation\ValidityViolationFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\ValidityViolationInterface;

final class ValidityViolationFactory implements ValidityViolationFactoryInterface
{
    public function createNullableViolation(BreadCrumbPathInterface $breadCrumbPath): ValidityViolationInterface
    {
        return new ValidityViolation(1001, 'Unexpected NULL value.', $breadCrumbPath);
    }

    public function createTypeViolation(
        string $type,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface {
        return new ValidityViolation(1002, "Type '${type}' expected.", $breadCrumbPath);
    }

    public function createRequiredViolation(BreadCrumbPathInterface $breadCrumbPath): ValidityViolationInterface
    {
        return new ValidityViolation(1003, 'Required.', $breadCrumbPath);
    }

    public function createUnexpectedViolation(BreadCrumbPathInterface $breadCrumbPath): ValidityViolationInterface
    {
        return new ValidityViolation(1004, 'Unexpected.', $breadCrumbPath);
    }

    public function createMinItemsViolation(
        int $min,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface {
        return new ValidityViolation(1005, "Items count has to be at least ${min}.", $breadCrumbPath);
    }

    public function createMaxItemsViolation(
        int $max,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface {
        return new ValidityViolation(1006, "Items count has to be at most ${max}.", $breadCrumbPath);
    }

    public function createUniqueViolation(BreadCrumbPathInterface $breadCrumbPath): ValidityViolationInterface
    {
        return new ValidityViolation(1007, 'Items have to be unique.', $breadCrumbPath);
    }

    public function createEnumViolation(
        array $choices,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface {
        $choicesString = \implode("', '", $choices);
        return new ValidityViolation(1008, "Value has to be one of ['${choicesString}'].", $breadCrumbPath);
    }

    public function createMinimumViolation(
        float $minimum,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface {
        return new ValidityViolation(1009, "Value has to be bigger or equal then ${minimum}.", $breadCrumbPath);
    }

    public function createExclusiveMinimumViolation(
        float $minimum,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface {
        return new ValidityViolation(1010, "Value has to be bigger then ${minimum}.", $breadCrumbPath);
    }

    public function createMaximumViolation(
        float $maximum,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface {
        return new ValidityViolation(1011, "Value has to be less or equal then ${maximum}.", $breadCrumbPath);
    }

    public function createExclusiveMaximumViolation(
        float $maximum,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface {
        return new ValidityViolation(1012, "Value has to be less then ${maximum}.", $breadCrumbPath);
    }

    public function createMultipleOfViolation(
        float $multiplier,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface {
        return new ValidityViolation(1013, "Value has to be divisible by ${multiplier}.", $breadCrumbPath);
    }

    public function createMinLengthViolation(
        int $minLength,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface {
        return new ValidityViolation(1014, "Characters count has to be at least ${minLength}.", $breadCrumbPath);
    }

    public function createMaxLengthViolation(
        int $maxLength,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface {
        return new ValidityViolation(1015, "Characters count has to be at most ${maxLength}.", $breadCrumbPath);
    }

    public function createFormatViolation(
        string $format,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface {
        return new ValidityViolation(1016, "Value doesn't have format '${format}'.", $breadCrumbPath);
    }

    public function createPatternViolation(
        string $pattern,
        BreadCrumbPathInterface $breadCrumbPath
    ): ValidityViolationInterface {
        return new ValidityViolation(1017, "Value doesn't match pattern '${pattern}'.", $breadCrumbPath);
    }
}
