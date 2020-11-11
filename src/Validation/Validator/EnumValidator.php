<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPath;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\EnumSchema;

final class EnumValidator extends AbstractValidator
{
    private EnumSchema $schema;

    public function __construct(
        ValidationResultBuilderFactoryInterface $validationResultBuilderFactory,
        EnumSchema $schema
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

        if (! \is_string($data)) {
            $resultBuilder->addTypeViolation('string', $breadCrumbPath);
        } elseif (! \in_array($data, $this->schema->getEnum())) {
            $resultBuilder->addChoicesViolation($this->schema->getEnum(), $breadCrumbPath);
        }
    }
}