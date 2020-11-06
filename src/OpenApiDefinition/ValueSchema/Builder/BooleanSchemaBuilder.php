<?php

declare(strict_types=1);

namespace Lang\OpenApiDefinition\ValueSchema\Builder;

use Lang\OpenApiDefinition\ValueSchema\BooleanSchema;

/**
 * @method BooleanSchema build()
 */
class BooleanSchemaBuilder extends AbstractSchemaBuilder
{
    protected function createInstance(): BooleanSchema
    {
        return new BooleanSchema(
            $this->nullable,
            $this->description
        );
    }
}
