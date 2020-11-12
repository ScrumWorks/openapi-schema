<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPath;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderInterface;

abstract class AbstractNumberValidator extends AbstractValidator
{
    protected function validateNumberConstrains(
        ValidationResultBuilderInterface $resultBuilder,
        ?float $data,
        ?float $min,
        ?float $max,
        ?bool $exclusiveMin,
        ?bool $exclusiveMax,
        ?float $multipleOf,
        BreadCrumbPath $breadCrumbPath
    ): void {
        $exclusiveMin ??= false;
        $exclusiveMax ??= false;

        if ($min !== null && $exclusiveMin && $min >= $data) {
            $resultBuilder->addExclusiveMinimumViolation($min, $breadCrumbPath);
        }
        if ($min !== null && ! $exclusiveMin && $min > $data) {
            $resultBuilder->addMinimumViolation($min, $breadCrumbPath);
        }

        if ($max !== null && $exclusiveMax && $max <= $data) {
            $resultBuilder->addExclusiveMaximumViolation($max, $breadCrumbPath);
        }
        if ($max !== null && ! $exclusiveMax && $max < $data) {
            $resultBuilder->addMaximumViolation($max, $breadCrumbPath);
        }

        if ($multipleOf !== null && \abs(0 - \fmod($data, $multipleOf)) > 0.0001) {
            $resultBuilder->addMultipleOfViolation($multipleOf, $breadCrumbPath);
        }
    }

    protected function collectNumberViolationExamples(
        ValidationResultBuilderInterface $resultBuilder,
        ?float $min,
        ?float $max,
        ?bool $exclusiveMin,
        ?bool $exclusiveMax,
        ?float $multipleOf,
        BreadCrumbPath $breadCrumbPath
    ): void {
        $exclusiveMin ??= false;
        $exclusiveMax ??= false;

        if ($min !== null) {
            if ($exclusiveMin) {
                $resultBuilder->addExclusiveMinimumViolation($min, $breadCrumbPath);
            } else {
                $resultBuilder->addMinimumViolation($min, $breadCrumbPath);
            }
        }
        if ($max !== null) {
            if ($exclusiveMax) {
                $resultBuilder->addExclusiveMaximumViolation($max, $breadCrumbPath);
            } else {
                $resultBuilder->addMaximumViolation($max, $breadCrumbPath);
            }
        }
        if ($multipleOf !== null) {
            $resultBuilder->addMultipleOfViolation($multipleOf, $breadCrumbPath);
        }
    }
}
