<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\FloatSchema;

/**
 * @method FloatSchema build()
 */
final class FloatSchemaBuilder extends AbstractSchemaBuilder
{
    protected ?float $minimum = null;

    protected ?float $maximum = null;

    protected ?bool $exclusiveMinimum = null;

    protected ?bool $exclusiveMaximum = null;

    protected ?float $multipleOf = null;

    /**
     * @return static
     */
    public function withMinimum(?float $minimum)
    {
        $this->minimum = $minimum;
        return $this;
    }

    /**
     * @return static
     */
    public function withMaximum(?float $maximum)
    {
        $this->maximum = $maximum;
        return $this;
    }

    /**
     * @return static
     */
    public function withExclusiveMinimum(?bool $exclusiveMinimum)
    {
        $this->exclusiveMinimum = $exclusiveMinimum;
        return $this;
    }

    /**
     * @return static
     */
    public function withExclusiveMaximum(?bool $exclusiveMaximum)
    {
        $this->exclusiveMaximum = $exclusiveMaximum;
        return $this;
    }

    /**
     * @return static
     */
    public function withMultipleOf(?float $multipleOf)
    {
        $this->multipleOf = $multipleOf;
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

    protected function createInstance(): FloatSchema
    {
        return new FloatSchema(
            $this->minimum,
            $this->maximum,
            $this->exclusiveMinimum,
            $this->exclusiveMaximum,
            $this->multipleOf,
            $this->nullable,
            $this->description
        );
    }
}
