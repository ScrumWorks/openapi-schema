<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPath;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema;

final class IntegerValidator extends AbstractNumberValidator
{
    private IntegerSchema $schema;

    public function __construct(
        ValidationResultBuilderFactoryInterface $validationResultBuilderFactory,
        IntegerSchema $schema
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

        if (! \is_int($data)) {
            $resultBuilder->addTypeViolation('integer', $breadCrumbPath);
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

        $resultBuilder->addTypeViolation('integer', $breadCrumbPath);
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
