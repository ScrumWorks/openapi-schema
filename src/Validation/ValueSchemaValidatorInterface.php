<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation;

use ScrumWorks\OpenApiSchema\SchemaCollection\IClassSchemaCollection;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

interface ValueSchemaValidatorInterface
{
    /**
     * @param mixed $data
     */
    public function validate(
        ValueSchemaInterface $schema,
        IClassSchemaCollection $classSchemaCollection,
        $data,
        ?BreadCrumbPathInterface $breadCrumbPath = null
    ): ValidationResultInterface;

    /**
     * @return ValidityViolationInterface[]
     */
    public function getPossibleViolationExamples(
        ValueSchemaInterface $schema,
        IClassSchemaCollection $classSchemaCollection,
        ?BreadCrumbPathInterface $breadCrumbPath = null
    ): array;
}
