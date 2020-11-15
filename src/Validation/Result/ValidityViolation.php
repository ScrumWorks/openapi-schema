<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Result;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;
use ScrumWorks\OpenApiSchema\Validation\ValidityViolationInterface;

final class ValidityViolation implements ValidityViolationInterface
{
    private int $violationCode;

    private string $message;

    private BreadCrumbPathInterface $breadCrumbPath;

    public function __construct(int $violationCode, string $message, BreadCrumbPathInterface $breadCrumbPath)
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

    public function getBreadCrumbPath(): BreadCrumbPathInterface
    {
        return $this->breadCrumbPath;
    }
}
