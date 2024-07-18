<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

interface ValueSchemaInterface
{
    public function isNullable(): bool;

    public function getDescription(): ?string;

    public function getSchemaName(): ?string;

    public function isDeprecated(): bool;

    /**
     * @return array<string, mixed>
     */
    public function getMetaData(): array;
}
