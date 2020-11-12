<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPath;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\FloatSchema;

final class FloatValidator extends AbstractNumberValidator
{
    private FloatSchema $schema;

    public function __construct(
        ValidationResultBuilderFactoryInterface $validationResultBuilderFactory,
        FloatSchema $schema
    ) {
        parent::__construct($validationResultBuilderFactory, $schema);

        $this->schema = $schema;
    }

    protected function doValidation(
        ValidationResultBuilderInterface $resultBuilder,
        $data,
        BreadCrumbPath $breadCrumbPath
    ): void {
        if (! $this->validateNullable($resultBuilder, $data, $breadCrumbPath)) {
            return;
        }

        if (\is_int($data)) {
            $data = (float) $data;
        }

        if (! \is_float($data)) {
            $resultBuilder->addTypeViolation('number', $breadCrumbPath);
            return;
        }

        $this->validateNumberConstrains(
            $resultBuilder,
            $data,
            $this->schema->getMinimum(),
            $this->schema->getMaximum(),
            $this->schema->getExclusiveMinimum(),
            $this->schema->getExclusiveMaximum(),
            $this->schema->getMultipleOf(),
            $breadCrumbPath,
        );
    }

    protected function collectPossibleViolationExamples(
        ValidationResultBuilderInterface $resultBuilder,
        BreadCrumbPath $breadCrumbPath
    ): void {
        parent::collectPossibleViolationExamples($resultBuilder, $breadCrumbPath);

        $resultBuilder->addTypeViolation('number', $breadCrumbPath);
        $this->collectNumberViolationExamples(
            $resultBuilder,
            $this->schema->getMinimum(),
            $this->schema->getMaximum(),
            $this->schema->getExclusiveMinimum(),
            $this->schema->getExclusiveMaximum(),
            $this->schema->getMultipleOf(),
            $breadCrumbPath,
        );
    }
}
