<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

/**
 * @method StringSchema build()
 */
class StringSchemaBuilder extends AbstractSchemaBuilder
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

    protected function createInstance(): StringSchema
    {
        return new StringSchema(
            $this->minLength,
            $this->maxLength,
            $this->format,
            $this->pattern,
            $this->nullable,
            $this->description
        );
    }
}
