<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;

interface SchemaParserInterface
{
    public function getEntitySchema(string $class): ObjectSchema;
}
