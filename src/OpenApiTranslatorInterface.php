<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

interface OpenApiTranslatorInterface
{
    public function translateValueSchema(ValueSchemaInterface $valueSchema): array;
}
