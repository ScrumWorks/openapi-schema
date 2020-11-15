<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;
use ScrumWorks\OpenApiSchema\Validation\ValidationResultInterface;
use ScrumWorks\OpenApiSchema\Validation\ValueSchemaValidatorInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

final class ValueSchemaValidator implements ValueSchemaValidatorInterface
{
    private ValidatorFactory $validatorFactory;

    public function __construct(ValidatorFactory $validatorFactory)
    {
        $this->validatorFactory = $validatorFactory;
    }

    public function validate(
        ValueSchemaInterface $schema,
        $data,
        ?BreadCrumbPathInterface $breadCrumbPath = null
    ): ValidationResultInterface {
        return $this->validatorFactory->createValidator($schema, $this)->validate($data, $breadCrumbPath);
    }

    public function getPossibleViolationExamples(
        ValueSchemaInterface $schema,
        ?BreadCrumbPathInterface $breadCrumbPath = null
    ): array {
        return $this->validatorFactory->createValidator($schema, $this)->getPossibleViolationExamples($breadCrumbPath);
    }
}
