<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema;

/**
 * @method IntegerSchema build()
 */
final class IntegerSchemaBuilder extends AbstractSchemaBuilder
{
    private ?int $minimum = null;

    private ?int $maximum = null;

    private ?bool $exclusiveMinimum = null;

    private ?bool $exclusiveMaximum = null;

    private ?int $multipleOf = null;

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
