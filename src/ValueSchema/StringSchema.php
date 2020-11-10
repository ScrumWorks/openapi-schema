<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

/**
 * @property-read ?int $minLength
 * @property-read ?int $maxLength
 * @property-read ?int $format
 * @property-read ?int $pattern
 */
class StringSchema extends AbstractValueSchema
{
    protected ?int $minLength;
    protected ?int $maxLength;
    protected ?string $format;
    protected ?string $pattern;

    /**
     * @TODO $format and $pattern is probably exclusive
     * @TODO $format enum?
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
