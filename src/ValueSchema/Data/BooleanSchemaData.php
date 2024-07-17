<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Data;

use ScrumWorks\OpenApiSchema\ValueSchema\BooleanSchema;

class BooleanSchemaData extends AbstractValueSchema implements BooleanSchema
{
    protected function validate(): void
    {
    }
}
