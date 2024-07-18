<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\Data\IntegerSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema;

final class IntegerSchemaBuilder extends AbstractSchemaBuilder
{
    protected ?int $minimum = null;

    protected ?int $maximum = null;

    protected ?bool $exclusiveMinimum = null;

    protected ?bool $exclusiveMaximum = null;

    protected ?int $multipleOf = null;

    protected ?int $example = null;

    public function withMinimum(?int $minimum): self
    {
        $this->minimum = $minimum;
        return $this;
    }

    public function withMaximum(?int $maximum): self
    {
        $this->maximum = $maximum;
        return $this;
    }

    public function withExclusiveMinimum(?bool $exclusiveMinimum): self
    {
        $this->exclusiveMinimum = $exclusiveMinimum;
        return $this;
    }

    public function withExclusiveMaximum(?bool $exclusiveMaximum): self
    {
        $this->exclusiveMaximum = $exclusiveMaximum;
        return $this;
    }

    public function withMultipleOf(?int $multipleOf): self
    {
        $this->multipleOf = $multipleOf;
        return $this;
    }

    public function withExample(?int $example): self
    {
        $this->example = $example;
        return $this;
    }

    public function getMinimum(): ?int
    {
        return $this->minimum;
    }

    public function getMaximum(): ?int
    {
        return $this->maximum;
    }

    public function getExclusiveMinimum(): ?bool
    {
        return $this->exclusiveMinimum;
    }

    public function getExclusiveMaximum(): ?bool
    {
        return $this->exclusiveMaximum;
    }

    public function getMultipleOf(): ?int
    {
        return $this->multipleOf;
    }

    public function getExample(): ?int
    {
        return $this->example;
    }

    public function build(): IntegerSchema
    {
        return new IntegerSchemaData(
            $this->minimum,
            $this->maximum,
            $this->exclusiveMinimum,
            $this->exclusiveMaximum,
            $this->multipleOf,
            $this->example,
            $this->nullable,
            $this->description,
            $this->schemaName,
            $this->deprecated,
            $this->metaData,
        );
    }
}
