<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation;

interface ValidityViolationInterface
{
    public function getViolationCode(): int;

    public function getMessage(): string;

    public function getBreadCrumbPath(): BreadCrumbPathInterface;
}
