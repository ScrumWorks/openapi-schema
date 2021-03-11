<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\ReferencedSchemaBag;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilder;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactory;
use ScrumWorks\OpenApiSchema\ValueSchema\EnumSchema;

final class EnumValidator extends AbstractValidator
{
    private EnumSchema $schema;

    public function __construct(
        BreadCrumbPathFactoryInterface $breadCrumbPathFactory,
        ValidationResultBuilderFactory $validationResultBuilderFactory,
        EnumSchema $schema
    ) {
        parent::__construct($breadCrumbPathFactory, $validationResultBuilderFactory, $schema);

        $this->schema = $schema;
    }

    protected function doValidation(
        ValidationResultBuilder $resultBuilder,
        $data,
        ReferencedSchemaBag $referencedSchemaBag,
        BreadCrumbPathInterface $breadCrumbPath
    ): void {
        if (! $this->validateNullable($resultBuilder, $data, $breadCrumbPath)) {
            return;
        }

        if (! \is_string($data)) {
            $resultBuilder->addTypeViolation('string', $breadCrumbPath);
        } elseif (! \in_array($data, $this->schema->getEnum())) {
            $resultBuilder->addEnumViolation($this->schema->getEnum(), $breadCrumbPath);
        }
    }

    protected function collectPossibleViolationExamples(
        ValidationResultBuilder $resultBuilder,
        ReferencedSchemaBag $referencedSchemaBag,
        BreadCrumbPathInterface $breadCrumbPath
    ): void {
        parent::collectPossibleViolationExamples($resultBuilder, $referencedSchemaBag, $breadCrumbPath);

        $resultBuilder->addTypeViolation('string', $breadCrumbPath);
        $resultBuilder->addEnumViolation($this->schema->getEnum(), $breadCrumbPath);
    }
}
