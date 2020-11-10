<?php

namespace ScrumWorks\OpenApiSchema;

use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;

interface SchemaParserInterface
{
    public function getEntitySchema(string $class): ObjectSchema;
}
