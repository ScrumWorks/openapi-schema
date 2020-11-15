<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Result;

use ScrumWorks\OpenApiSchema\Validation\ValidityViolationFactoryInterface;

class ValidationResultBuilderFactory
{
    protected ValidityViolationFactoryInterface $validityViolationFactory;

    public function __construct(ValidityViolationFactoryInterface $validityViolationFactory)
    {
        $this->validityViolationFactory = $validityViolationFactory;
    }

    public function create(): ValidationResultBuilder
    {
        return new ValidationResultBuilder($this->validityViolationFactory);
    }
}
