<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Data;

use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\EnumSchema;

final class EnumSchemaData extends AbstractValueSchema implements EnumSchema
{
    /**
     * @param string[] $enum
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
     * @return string[]
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

        // TODO: maybe change this later for also support int, etc
        foreach ($this->enum as $enum) {
            if (! \is_string($enum)) {
                throw new InvalidArgumentException('Only strings are allowed for enum properties');
            }
        }
    }
}
