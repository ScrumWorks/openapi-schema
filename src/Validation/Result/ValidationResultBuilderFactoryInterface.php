<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Result;

interface ValidationResultBuilderFactoryInterface
{
    public function create(): ValidationResultBuilderInterface;
}
