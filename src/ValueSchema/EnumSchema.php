<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

interface EnumSchema extends ValueSchemaInterface
{
    /**
     * @return string[]|int[]
     */
    public function getEnum(): array;
}
