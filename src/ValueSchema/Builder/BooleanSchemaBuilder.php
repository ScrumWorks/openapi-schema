<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\BooleanSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\BooleanSchemaData;

final class BooleanSchemaBuilder extends AbstractSchemaBuilder
{
    public function build(): BooleanSchema
    {
        return new BooleanSchemaData($this->nullable, $this->description, $this->schemaName, $this->deprecated);
    }
}
