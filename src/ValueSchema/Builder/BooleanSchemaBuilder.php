<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\BooleanSchema;

final class BooleanSchemaBuilder extends AbstractSchemaBuilder
{
    public function build(): BooleanSchema
    {
        return new BooleanSchema($this->nullable, $this->description, $this->schemaName);
    }
}
