<?php

namespace ScrumWorks\OpenApiSchema;

use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

interface OpenApiTranslatorInterface
{
    public function translateValueSchema(ValueSchemaInterface $valueSchema): array;
}
