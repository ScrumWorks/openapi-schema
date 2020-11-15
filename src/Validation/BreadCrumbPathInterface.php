<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation;

interface BreadCrumbPathInterface
{
    public function __toString();

    public function withNextBreadCrumb(string $breadCrumb): self;

    public function withIndex(int $index): self;
}
