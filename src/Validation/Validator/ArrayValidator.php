<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\ReferencedSchemaBag;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilder;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactory;
use ScrumWorks\OpenApiSchema\Validation\ValueSchemaValidatorInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\ArraySchema;

final class ArrayValidator extends AbstractValidator
{
    private ArraySchema $schema;

    private ValueSchemaValidatorInterface $valueValidator;

    public function __construct(
        BreadCrumbPathFactoryInterface $breadCrumbPathFactory,
        ValidationResultBuilderFactory $validationResultBuilderFactory,
        ArraySchema $schema,
        ValueSchemaValidatorInterface $valueValidator
    ) {
        parent::__construct($breadCrumbPathFactory, $validationResultBuilderFactory, $schema);

        $this->schema = $schema;
        $this->valueValidator = $valueValidator;
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

        if (! \is_array($data)) {
            $resultBuilder->addTypeViolation('array', $breadCrumbPath);
            return;
        }

        $count = \count($data);
        if (($min = $this->schema->getMinItems()) !== null && $min > $count) {
            $resultBuilder->addMinItemsViolation($min, $breadCrumbPath);
        }
        if (($max = $this->schema->getMaxItems()) !== null && $max < $count) {
            $resultBuilder->addMaxItemsViolation($max, $breadCrumbPath);
        }
        if (($this->schema->getUniqueItems() ?? false) && $count !== \count(\array_unique($data))) {
            $resultBuilder->addUniqueViolation($breadCrumbPath);
        }

        $itemsSchema = $this->schema->getItemsSchema();
        foreach ($data as $itemIndex => $itemData) {
            $propertyValidationResult = $this->valueValidator->validate(
                $itemsSchema,
                $referencedSchemaBag,
                $itemData,
                $breadCrumbPath->withIndex($itemIndex)
            );
            $resultBuilder->mergeResult($propertyValidationResult);
        }
    }

    protected function collectPossibleViolationExamples(
        ValidationResultBuilder $resultBuilder,
        ReferencedSchemaBag $referencedSchemaBag,
        BreadCrumbPathInterface $breadCrumbPath
    ): void {
        parent::collectPossibleViolationExamples($resultBuilder, $referencedSchemaBag, $breadCrumbPath);

        $resultBuilder->addTypeViolation('array', $breadCrumbPath);

        if (($min = $this->schema->getMinItems()) !== null) {
            $resultBuilder->addMinItemsViolation($min, $breadCrumbPath);
        }
        if (($max = $this->schema->getMaxItems()) !== null) {
            $resultBuilder->addMaxItemsViolation($max, $breadCrumbPath);
        }
        if ($this->schema->getUniqueItems() ?? false) {
            $resultBuilder->addUniqueViolation($breadCrumbPath);
        }

        $resultBuilder->mergeViolations(
            $this->valueValidator->getPossibleViolationExamples(
                $this->schema->getItemsSchema(),
                $referencedSchemaBag,
                $breadCrumbPath->withIndex(0)
            )
        );
    }
}
