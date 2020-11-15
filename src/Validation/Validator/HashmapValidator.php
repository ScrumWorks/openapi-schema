<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilder;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactory;
use ScrumWorks\OpenApiSchema\Validation\ValueSchemaValidatorInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\HashmapSchema;

final class HashmapValidator extends AbstractValidator
{
    private HashmapSchema $schema;

    private ValueSchemaValidatorInterface $valueValidator;

    public function __construct(
        BreadCrumbPathFactoryInterface $breadCrumbPathFactory,
        ValidationResultBuilderFactory $validationResultBuilderFactory,
        HashmapSchema $schema,
        ValueSchemaValidatorInterface $valueValidator
    ) {
        parent::__construct($breadCrumbPathFactory, $validationResultBuilderFactory, $schema);

        $this->schema = $schema;
        $this->valueValidator = $valueValidator;
    }

    protected function doValidation(
        ValidationResultBuilder $resultBuilder,
        $data,
        BreadCrumbPathInterface $breadCrumbPath
    ): void {
        if (! $this->validateNullable($resultBuilder, $data, $breadCrumbPath)) {
            return;
        }

        if (! \is_object($data)) {
            $resultBuilder->addTypeViolation('object', $breadCrumbPath);
            return;
        }

        foreach ($this->schema->getRequiredProperties() as $requiredProperty) {
            if (! \property_exists($data, $requiredProperty)) {
                $resultBuilder->addRequiredViolation($breadCrumbPath->withNextBreadCrumb($requiredProperty));
            }
        }

        $itemsSchema = $this->schema->getItemsSchema();
        // @phpstan-ignore-next-line
        foreach ($data as $itemKey => $itemData) {
            $propertyValidationResult = $this->valueValidator->validate(
                $itemsSchema,
                $itemData,
                $breadCrumbPath->withNextBreadCrumb($itemKey)
            );
            $resultBuilder->mergeResult($propertyValidationResult);
        }
    }

    protected function collectPossibleViolationExamples(
        ValidationResultBuilder $resultBuilder,
        BreadCrumbPathInterface $breadCrumbPath
    ): void {
        parent::collectPossibleViolationExamples($resultBuilder, $breadCrumbPath);

        $resultBuilder->addTypeViolation('object', $breadCrumbPath);

        foreach ($this->schema->getRequiredProperties() as $propertyName) {
            $resultBuilder->addRequiredViolation($breadCrumbPath->withNextBreadCrumb($propertyName));
        }

        $resultBuilder->mergeViolations(
            $this->valueValidator->getPossibleViolationExamples(
                $this->schema->getItemsSchema(),
                $breadCrumbPath->withNextBreadCrumb('-key-')
            ),
        );
    }
}
