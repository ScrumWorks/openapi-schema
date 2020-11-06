<?php

namespace Lang\OpenApiDefinition;

use Lang\OpenApiDefinition\ValueSchema\ValueSchemaInterface;

interface OpenApiTranslatorInterface
{
    public function translateValueSchema(ValueSchemaInterface $valueSchema): array;
}
