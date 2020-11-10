<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\BooleanSchema;

/**
 * @method BooleanSchema build()
 */
final class BooleanSchemaBuilder extends AbstractSchemaBuilder
{
    protected function createInstance(): BooleanSchema
    {
        return new BooleanSchema($this->nullable, $this->description);
    }
}
