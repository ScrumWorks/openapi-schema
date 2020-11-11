<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPath;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderInterface;
use ScrumWorks\OpenApiSchema\Validation\ValueValidator;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;

final class ObjectValidator extends AbstractValidator
{
    private ObjectSchema $schema;

    private ValueValidator $valueValidator;

    public function __construct(
        ValidationResultBuilderFactoryInterface $validationResultBuilderFactory,
        ObjectSchema $schema,
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

        $propertySchemas = $this->schema->getPropertiesSchemas();
        // @phpstan-ignore-next-line
        foreach ($data as $propertyName => $propertyData) {
            if (! \array_key_exists($propertyName, $propertySchemas)) {
                $resultBuilder->addUnexpectedViolation($breadCrumbPath->withNextBreadCrumb($propertyName));
            } else {
                $propertyValidationResult = $this->valueValidator->validate(
                    $propertySchemas[$propertyName],
                    $propertyData,
                    $breadCrumbPath->withNextBreadCrumb($propertyName))
                ;
                $resultBuilder->mergeResult($propertyValidationResult);
            }
        }
    }
}
