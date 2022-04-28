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

    protected ?string $example = null;

    public function withMinLength(?int $minLength): self
    {
        $this->minLength = $minLength;
        return $this;
    }

    public function withMaxLength(?int $maxLength): self
    {
        $this->maxLength = $maxLength;
        return $this;
    }

    public function withFormat(?string $format): self
    {
        $this->format = $format;
        return $this;
    }

    public function withPattern(?string $pattern): self
    {
        $this->pattern = $pattern;
        return $this;
    }

    public function withExample(?string $example): self
    {
        $this->example = $example;
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

    public function getExample(): ?string
    {
        return $this->example;
    }

    public function build(): StringSchema
    {
        return new StringSchema(
            $this->minLength,
            $this->maxLength,
            $this->format,
            $this->pattern,
            $this->example,
            $this->nullable,
            $this->description,
            $this->schemaName,
            $this->deprecated
        );
    }
}
