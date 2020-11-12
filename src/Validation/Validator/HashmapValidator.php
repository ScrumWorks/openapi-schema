<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPath;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderInterface;
use ScrumWorks\OpenApiSchema\Validation\ValueValidator;
use ScrumWorks\OpenApiSchema\ValueSchema\HashmapSchema;

final class HashmapValidator extends AbstractValidator
{
    private HashmapSchema $schema;

    private ValueValidator $valueValidator;

    public function __construct(
        ValidationResultBuilderFactoryInterface $validationResultBuilderFactory,
        HashmapSchema $schema,
        ValueValidator $valueValidator
    ) {
        parent::__construct($validationResultBuilderFactory, $schema);

        $this->schema = $schema;
        $this->valueValidator = $valueValidator;
    }

    protected function doValidation(
        ValidationResultBuilderInterface $resultBuilder,
        $data,
        BreadCrumbPath $breadCrumbPath
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
        ValidationResultBuilderInterface $resultBuilder,
        BreadCrumbPath $breadCrumbPath
    ): void {
        parent::collectPossibleViolationExamples($resultBuilder, $breadCrumbPath);

        $resultBuilder->addTypeViolation('object', $breadCrumbPath);
        $resultBuilder->addRequiredViolation($breadCrumbPath->withNextBreadCrumb('key'));
        $resultBuilder->mergeViolations(
            $this->valueValidator->getPossibleViolationExamples(
                $this->schema->getItemsSchema(),
                $breadCrumbPath->withNextBreadCrumb('key')
            ),
        );
    }
}
