<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

interface EnumSchema extends ValueSchemaInterface
{
    /**
     * @return string[]
     */
    public function getEnum(): array;
}
