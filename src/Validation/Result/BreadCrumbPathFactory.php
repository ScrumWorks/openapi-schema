<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Result;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;

final class BreadCrumbPathFactory implements BreadCrumbPathFactoryInterface
{
    public function create(): BreadCrumbPathInterface
    {
        return new BreadCrumbPath();
    }
}
