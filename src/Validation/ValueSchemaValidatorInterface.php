<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation;

use ScrumWorks\OpenApiSchema\ReferencedSchemaBag;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

interface ValueSchemaValidatorInterface
{
    /**
     * @param mixed $data
     */
    public function validate(
        ValueSchemaInterface $schema,
        ReferencedSchemaBag $referencedSchemaBag,
        $data,
        ?BreadCrumbPathInterface $breadCrumbPath = null
    ): ValidationResultInterface;

    /**
     * @return ValidityViolationInterface[]
     */
    public function getPossibleViolationExamples(
        ValueSchemaInterface $schema,
        ReferencedSchemaBag $referencedSchemaBag,
        ?BreadCrumbPathInterface $breadCrumbPath = null
    ): array;
}
