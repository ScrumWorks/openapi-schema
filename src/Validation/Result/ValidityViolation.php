<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Result;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPath;

final class ValidityViolation
{
    private int $violationCode;

    private string $message;

    private BreadCrumbPath $breadCrumbPath;

    public function __construct(int $violationCode, string $message, BreadCrumbPath $breadCrumbPath)
    {
        $this->violationCode = $violationCode;
        $this->message = $message;
        $this->breadCrumbPath = $breadCrumbPath;
    }

    public function getViolationCode(): int
    {
        return $this->violationCode;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getBreadCrumbPath(): BreadCrumbPath
    {
        return $this->breadCrumbPath;
    }
}
