<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\ReferencedSchemaBag;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilder;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactory;
use ScrumWorks\OpenApiSchema\Validation\ValueSchemaValidatorInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;

final class ObjectValidator extends AbstractValidator
{
    private ObjectSchema $schema;

    private ValueSchemaValidatorInterface $valueValidator;

    public function __construct(
        BreadCrumbPathFactoryInterface $breadCrumbPathFactory,
        ValidationResultBuilderFactory $validationResultBuilderFactory,
        ObjectSchema $schema,
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

        if (! \is_object($data)) {
            $resultBuilder->addTypeViolation('object', $breadCrumbPath);
            return;
        }

        foreach ($this->schema->getRequiredProperties() as $requiredProperty) {
            if (! \property_exists($data, $requiredProperty)) {
                $resultBuilder->addRequiredViolation($breadCrumbPath->withNextBreadCrumb($requiredProperty));
            }
        }

        $propertySchemas = $this->schema->getPropertiesSchemas();
        // @phpstan-ignore-next-line
        foreach ($data as $propertyName => $propertyData) {
            if (! \array_key_exists($propertyName, $propertySchemas)) {
                $resultBuilder->addUnexpectedViolation($breadCrumbPath->withNextBreadCrumb($propertyName));
            } else {
                $propertyValidationResult = $this->valueValidator->validate(
                    $propertySchemas[$propertyName],
                    $referencedSchemaBag,
                    $propertyData,
                    $breadCrumbPath->withNextBreadCrumb($propertyName))
                ;
                $resultBuilder->mergeResult($propertyValidationResult);
            }
        }
    }

    protected function collectPossibleViolationExamples(
        ValidationResultBuilder $resultBuilder,
        ReferencedSchemaBag $referencedSchemaBag,
        BreadCrumbPathInterface $breadCrumbPath
    ): void {
        parent::collectPossibleViolationExamples($resultBuilder, $referencedSchemaBag, $breadCrumbPath);

        $resultBuilder->addTypeViolation('object', $breadCrumbPath);
        $resultBuilder->addUnexpectedViolation($breadCrumbPath->withNextBreadCrumb('-unknown-property-'));

        foreach ($this->schema->getRequiredProperties() as $propertyName) {
            $resultBuilder->addRequiredViolation($breadCrumbPath->withNextBreadCrumb($propertyName));
        }

        foreach ($this->schema->getPropertiesSchemas() as $propertyName => $propertySchema) {
            $resultBuilder->mergeViolations(
                $this->valueValidator->getPossibleViolationExamples(
                    $propertySchema,
                    $referencedSchemaBag,
                    $breadCrumbPath->withNextBreadCrumb($propertyName)
                ),
            );
        }
    }
}
