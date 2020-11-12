<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPath;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderInterface;
use ScrumWorks\OpenApiSchema\Validation\ValueValidator;
use ScrumWorks\OpenApiSchema\ValueSchema\ArraySchema;

final class ArrayValidator extends AbstractValidator
{
    private ArraySchema $schema;

    private ValueValidator $valueValidator;

    public function __construct(
        ValidationResultBuilderFactoryInterface $validationResultBuilderFactory,
        ArraySchema $schema,
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

        if (! \is_array($data)) {
            $resultBuilder->addTypeViolation('array', $breadCrumbPath);
            return;
        }

        $count = \count($data);
        if (($min = $this->schema->getMinItems()) !== null && $min > $count) {
            $resultBuilder->addMinCountViolation($min, $breadCrumbPath);
        }
        if (($max = $this->schema->getMaxItems()) !== null && $max < $count) {
            $resultBuilder->addMaxCountViolation($max, $breadCrumbPath);
        }
        if (($this->schema->getUniqueItems() ?? false) && $count !== \count(\array_unique($data))) {
            $resultBuilder->addUniqueViolation($breadCrumbPath);
        }

        $itemsSchema = $this->schema->getItemsSchema();
        foreach ($data as $itemIndex => $itemData) {
            $propertyValidationResult = $this->valueValidator->validate(
                $itemsSchema,
                $itemData,
                $breadCrumbPath->withIndex($itemIndex)
            );
            $resultBuilder->mergeResult($propertyValidationResult);
        }
    }

    protected function collectPossibleViolationExamples(
        ValidationResultBuilderInterface $resultBuilder,
        BreadCrumbPath $breadCrumbPath
    ): void {
        parent::collectPossibleViolationExamples($resultBuilder, $breadCrumbPath);

        $resultBuilder->addTypeViolation('array', $breadCrumbPath);

        if (($min = $this->schema->getMinItems()) !== null) {
            $resultBuilder->addMinCountViolation($min, $breadCrumbPath);
        }
        if (($max = $this->schema->getMaxItems()) !== null) {
            $resultBuilder->addMaxCountViolation($max, $breadCrumbPath);
        }
        if ($this->schema->getUniqueItems() ?? false) {
            $resultBuilder->addUniqueViolation($breadCrumbPath);
        }

        $resultBuilder->mergeViolations(
            $this->valueValidator->getPossibleViolationExamples(
                $this->schema->getItemsSchema(),
                $breadCrumbPath->withIndex(0)
            )
        );
    }
}
