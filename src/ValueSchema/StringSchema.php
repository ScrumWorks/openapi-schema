<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

final class StringSchema extends AbstractValueSchema
{
    private ?int $minLength;

    private ?int $maxLength;

    private ?string $format;

    private ?string $pattern;

    /**
     * @TODO $format and $pattern is probably exclusive
     */
    public function __construct(
        ?int $minLength,
        ?int $maxLength,
        ?string $format,
        ?string $pattern,
        bool $nullable,
        ?string $description
    ) {
        parent::__construct($nullable, $description);

        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
        $this->format = $format;
        $this->pattern = $pattern;
    }

    public function getMinLength(): ?int
    {
        return $this->minLength;
    }

    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function getPattern(): ?string
    {
        return $this->pattern;
    }
}
