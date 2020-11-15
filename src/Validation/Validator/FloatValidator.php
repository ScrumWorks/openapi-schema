<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilder;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactory;
use ScrumWorks\OpenApiSchema\ValueSchema\FloatSchema;

final class FloatValidator extends AbstractNumberValidator
{
    private FloatSchema $schema;

    public function __construct(
        BreadCrumbPathFactoryInterface $breadCrumbPathFactory,
        ValidationResultBuilderFactory $validationResultBuilderFactory,
        FloatSchema $schema
    ) {
        parent::__construct($breadCrumbPathFactory, $validationResultBuilderFactory, $schema);

        $this->schema = $schema;
    }

    protected function doValidation(
        ValidationResultBuilder $resultBuilder,
        $data,
        BreadCrumbPathInterface $breadCrumbPath
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
        ValidationResultBuilder $resultBuilder,
        BreadCrumbPathInterface $breadCrumbPath
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
