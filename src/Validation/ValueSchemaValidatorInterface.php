<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation;

use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

interface ValueSchemaValidatorInterface
{
    /**
     * @param mixed $data
     */
    public function validate(
        ValueSchemaInterface $schema,
        $data,
        ?BreadCrumbPathInterface $breadCrumbPath = null
    ): ValidationResultInterface;

    /**
     * @return ValidityViolationInterface[]
     */
    public function getPossibleViolationExamples(
        ValueSchemaInterface $schema,
        ?BreadCrumbPathInterface $breadCrumbPath = null
    ): array;
}
