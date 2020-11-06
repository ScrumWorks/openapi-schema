<?php

declare(strict_types=1);

namespace Lang\OpenApiDefinition\ValueSchema\Builder;

use Lang\OpenApiDefinition\ValueSchema\FloatSchema;

/**
 * @method FloatSchema build()
 */
class FloatSchemaBuilder extends AbstractSchemaBuilder
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
    public function withExclusiveMinimum(?float $exclusiveMinimum)
    {
        $this->exclusiveMinimum = $exclusiveMinimum;
        return $this;
    }

    /**
     * @return static
     */
    public function withExclusiveMaximum(?float $exclusiveMaximum)
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