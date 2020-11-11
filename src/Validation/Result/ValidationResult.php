<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Result;

final class ValidationResult
{
    private array $validityViolations;

    /**
     * @param ValidityViolation[] $validityViolations
     */
    public function __construct(array $validityViolations)
    {
        $this->validityViolations = $validityViolations;
    }

    public function isValid(): bool
    {
        return ! \count($this->validityViolations);
    }

    /**
     * @return ValidityViolation[]
     */
    public function getViolations(): array
    {
        return $this->validityViolations;
    }
}
