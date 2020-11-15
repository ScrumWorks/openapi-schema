<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\Validation\_Support;

use ScrumWorks\OpenApiSchema\Validation\Result\BreadCrumbPathFactory;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactory;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidityViolationFactory;
use ScrumWorks\OpenApiSchema\Validation\Validator\ValidatorFactory;
use ScrumWorks\OpenApiSchema\Validation\Validator\ValueSchemaValidator;
use ScrumWorks\OpenApiSchema\Validation\ValueSchemaValidatorInterface;

trait CreateValidatorTrait
{
    public function createValueValidator(): ValueSchemaValidatorInterface
    {
        return new ValueSchemaValidator(
            new ValidatorFactory(
                new BreadCrumbPathFactory(),
                new ValidationResultBuilderFactory(new ValidityViolationFactory())
            )
        );
    }
}
