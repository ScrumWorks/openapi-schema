<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPath;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderInterface;

final class MixedValidator extends AbstractValidator
{
    protected function doValidation(
        ValidationResultBuilderInterface $resultBuilder,
        $data,
        BreadCrumbPath $breadCrumbPath
    ): void {
        $this->validateNullable($resultBuilder, $data, $breadCrumbPath);
    }
}
