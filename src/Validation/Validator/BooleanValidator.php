<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilder;

final class BooleanValidator extends AbstractValidator
{
    protected function doValidation(
        ValidationResultBuilder $resultBuilder,
        $data,
        BreadCrumbPathInterface $breadCrumbPath
    ): void {
        if (! $this->validateNullable($resultBuilder, $data, $breadCrumbPath)) {
            return;
        }

        if (! \is_bool($data)) {
            $resultBuilder->addTypeViolation('boolean', $breadCrumbPath);
        }
    }

    protected function collectPossibleViolationExamples(
        ValidationResultBuilder $resultBuilder,
        BreadCrumbPathInterface $breadCrumbPath
    ): void {
        parent::collectPossibleViolationExamples($resultBuilder, $breadCrumbPath);

        $resultBuilder->addTypeViolation('boolean', $breadCrumbPath);
    }
}
