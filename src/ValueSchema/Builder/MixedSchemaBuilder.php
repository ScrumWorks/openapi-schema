<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\Data\MixedSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\MixedSchema;

final class MixedSchemaBuilder extends AbstractSchemaBuilder
{
    public function build(): MixedSchema
    {
        return new MixedSchemaData(
            $this->nullable,
            $this->description,
            $this->schemaName,
            $this->deprecated,
            $this->metaData,
        );
    }
}
