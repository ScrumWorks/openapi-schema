<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Result;

use ScrumWorks\OpenApiSchema\Validation\ValidationResultInterface;
use ScrumWorks\OpenApiSchema\Validation\ValidityViolationInterface;

final class ValidationResult implements ValidationResultInterface
{
    /**
     * @var ValidityViolationInterface[]
     */
    private array $validityViolations;

    /**
     * @param ValidityViolationInterface[] $validityViolations
     */
    public function __construct(array $validityViolations)
    {
        $this->validityViolations = $validityViolations;
    }

    public function isValid(): bool
    {
        return ! \count($this->validityViolations);
    }

    public function getViolations(): array
    {
        return $this->validityViolations;
    }
}
