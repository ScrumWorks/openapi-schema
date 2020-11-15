<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation;

interface ValidationResultInterface
{
    public function isValid(): bool;

    /**
     * @return ValidityViolationInterface[]
     */
    public function getViolations(): array;
}
