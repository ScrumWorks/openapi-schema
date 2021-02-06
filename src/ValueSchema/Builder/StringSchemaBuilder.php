<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

final class StringSchemaBuilder extends AbstractSchemaBuilder
{
    protected ?int $minLength = null;

    protected ?int $maxLength = null;

    protected ?string $format = null;

    protected ?string $pattern = null;

    /**
     * @return static
     */
    public function withMinLength(?int $minLength)
    {
        $this->minLength = $minLength;
        return $this;
    }

    /**
     * @return static
     */
    public function withMaxLength(?int $maxLength)
    {
        $this->maxLength = $maxLength;
        return $this;
    }

    /**
     * @return static
     */
    public function withFormat(?string $format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return static
     */
    public function withPattern(?string $pattern)
    {
        $this->pattern = $pattern;
        return $this;
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

    public function build(): StringSchema
    {
        return new StringSchema(
            $this->minLength,
            $this->maxLength,
            $this->format,
            $this->pattern,
            $this->nullable,
            $this->description,
            $this->schemaName
        );
    }
}
