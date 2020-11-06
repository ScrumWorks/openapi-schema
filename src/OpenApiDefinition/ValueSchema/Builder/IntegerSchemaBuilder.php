<?php

declare(strict_types=1);

namespace Lang\OpenApiDefinition\ValueSchema\Builder;

use Lang\OpenApiDefinition\ValueSchema\IntegerSchema;

/**
 * @method IntegerSchema build()
 */
class IntegerSchemaBuilder extends AbstractSchemaBuilder
{
    protected ?int $minimum = null;
    protected ?int $maximum = null;
    protected ?bool $exclusiveMinimum = null;
    protected ?bool $exclusiveMaximum = null;
    protected ?int $multipleOf = null;

    /**
     * @return static
     */
    public function withMinimum(?int $minimum)
    {
        $this->minimum = $minimum;
        return $this;
    }

    /**
     * @return static
     */
    public function withMaximum(?int $maximum)
    {
        $this->maximum = $maximum;
        return $this;
    }

    /**
     * @return static
     */
    public function withExclusiveMinimum(?int $exclusiveMinimum)
    {
        $this->exclusiveMinimum = $exclusiveMinimum;
        return $this;
    }

    /**
     * @return static
     */
    public function withExclusiveMaximum(?int $exclusiveMaximum)
    {
        $this->exclusiveMaximum = $exclusiveMaximum;
        return $this;
    }

    /**
     * @return static
     */
    public function withMultipleOf(?int $multipleOf)
    {
        $this->multipleOf = $multipleOf;
        return $this;
    }

    protected function createInstance(): IntegerSchema
    {
        return new IntegerSchema(
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
