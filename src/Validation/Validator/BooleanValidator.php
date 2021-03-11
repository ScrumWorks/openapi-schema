<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\ReferencedSchemaBag;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilder;

final class BooleanValidator extends AbstractValidator
{
    protected function doValidation(
        ValidationResultBuilder $resultBuilder,
        $data,
        ReferencedSchemaBag $referencedSchemaBag,
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
        ReferencedSchemaBag $referencedSchemaBag,
        BreadCrumbPathInterface $breadCrumbPath
    ): void {
        parent::collectPossibleViolationExamples($resultBuilder, $referencedSchemaBag, $breadCrumbPath);

        $resultBuilder->addTypeViolation('boolean', $breadCrumbPath);
    }
}
