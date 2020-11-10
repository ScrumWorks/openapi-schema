<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\FloatSchema;

/**
 * @method FloatSchema build()
 */
final class FloatSchemaBuilder extends AbstractSchemaBuilder
{
    private ?float $minimum = null;

    private ?float $maximum = null;

    private ?bool $exclusiveMinimum = null;

    private ?bool $exclusiveMaximum = null;

    private ?float $multipleOf = null;

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
