<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator\Format;

interface FormatValidatorInterface
{
    public function hasValidFormat(string $string): bool;
}
