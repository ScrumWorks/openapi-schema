<?php

namespace ScrumWorks\OpenApiSchema\ValueSchema;

interface ValueSchemaInterface
{
    public function isNullable(): bool;

    public function getDescription(): ?string;
}
