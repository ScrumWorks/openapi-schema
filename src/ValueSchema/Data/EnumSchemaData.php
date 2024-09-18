<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Data;

use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\EnumSchema;

final class EnumSchemaData extends AbstractValueSchema implements EnumSchema
{
    /**
     * @param string[]|int[] $enum
     * @param array<string, mixed> $metaData
     */
    public function __construct(
        private readonly array $enum,
        bool $nullable = false,
        ?string $description = null,
        ?string $schemaName = null,
        bool $isDeprecated = false,
        array $metaData = [],
    ) {
        parent::__construct($nullable, $description, $schemaName, $isDeprecated, $metaData);
    }

    /**
     * @return string[]|int[]
     */
    public function getEnum(): array
    {
        return $this->enum;
    }

    protected function validate(): void
    {
        if (! $this->enum) {
            throw new InvalidArgumentException('Minimal one enum item is required');
        }
    }
}
