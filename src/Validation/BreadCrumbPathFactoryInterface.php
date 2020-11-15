<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation;

interface BreadCrumbPathFactoryInterface
{
    public function create(): BreadCrumbPathInterface;
}
