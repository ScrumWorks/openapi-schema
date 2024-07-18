<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\Data\FloatSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\FloatSchema;

final class FloatSchemaBuilder extends AbstractSchemaBuilder
{
    protected ?float $minimum = null;

    protected ?float $maximum = null;

    protected ?bool $exclusiveMinimum = null;

    protected ?bool $exclusiveMaximum = null;

    protected ?float $multipleOf = null;

    protected ?float $example = null;

    public function withMinimum(?float $minimum): self
    {
        $this->minimum = $minimum;
        return $this;
    }

    public function withMaximum(?float $maximum): self
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

    public function withMultipleOf(?float $multipleOf): self
    {
        $this->multipleOf = $multipleOf;
        return $this;
    }

    public function withExample(?float $example): self
    {
        $this->example = $example;
        return $this;
    }

    public function getMinimum(): ?float
    {
        return $this->minimum;
    }

    public function getMaximum(): ?float
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

    public function getMultipleOf(): ?float
    {
        return $this->multipleOf;
    }

    public function getExample(): ?float
    {
        return $this->example;
    }

    public function build(): FloatSchema
    {
        return new FloatSchemaData(
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
