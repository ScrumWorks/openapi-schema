<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\MixedSchema;

/**
 * @method MixedSchema build()
 */
final class MixedSchemaBuilder extends AbstractSchemaBuilder
{
    protected function createInstance(): MixedSchema
    {
        return new MixedSchema($this->nullable, $this->description);
    }
}
