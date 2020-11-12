<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\Validation\_Support;

use ScrumWorks\OpenApiSchema\Tests\Validation\TestValidationResultBuilder;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderInterface;
use ScrumWorks\OpenApiSchema\Validation\ValueValidator;

trait CreateValidatorTrait
{
    public function createValueValidator(): ValueValidator
    {
        $resultBuilderFactory = new class() implements ValidationResultBuilderFactoryInterface {
            public function create(): ValidationResultBuilderInterface
            {
                return new TestValidationResultBuilder();
            }
        };

        return new ValueValidator($resultBuilderFactory);
    }
}
