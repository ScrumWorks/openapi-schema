<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;

final class StringSchema extends AbstractValueSchema
{
    private ?int $minLength;

    private ?int $maxLength;

    private ?string $format;

    private ?string $pattern;

    public function __construct(
        ?int $minLength = null,
        ?int $maxLength = null,
        ?string $format = null,
        ?string $pattern = null,
        bool $nullable = false,
        ?string $description = null,
        $example = null
    ) {
        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
        $this->format = $format;
        $this->pattern = $pattern;

        parent::__construct($nullable, $description, $example);
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

    protected function validate(): void
    {
        if ($this->minLength !== null && $this->minLength < 0) {
            throw new InvalidArgumentException(\sprintf("Invalid value %d for argument 'minLength'", $this->minLength));
        }
        if ($this->maxLength !== null && $this->maxLength < 0) {
            throw new InvalidArgumentException(\sprintf("Invalid value %d for argument 'maxLength'", $this->maxLength));
        }
        if ($this->minLength !== null && $this->maxLength !== null && $this->maxLength < $this->minLength) {
            throw new InvalidArgumentException(\sprintf("Invalid value %d for argument 'maxLength'", $this->maxLength));
        }
        // TODO assert for $pattern
        // TODO $format and $pattern is probably exclusive
    }
}
