<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;

final class EnumSchema extends AbstractValueSchema
{
    /**
     * @var string[]
     */
    private array $enum;

    /**
     * @param string[] $enum
     */
    public function __construct(
        array $enum,
        bool $nullable = false,
        ?string $description = null,
        ?string $schemaName = null,
        bool $isDeprecated = false
    ) {
        $this->enum = $enum;

        parent::__construct($nullable, $description, $schemaName, $isDeprecated);
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
