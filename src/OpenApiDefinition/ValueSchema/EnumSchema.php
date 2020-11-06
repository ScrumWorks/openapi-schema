<?php

declare(strict_types=1);

namespace Lang\OpenApiDefinition\ValueSchema;

/**
 * @property-read string[] $enum
 */
class EnumSchema extends AbstractValueSchema
{
    protected array $enum;

    /**
     * @param string[] $enum
     */
    public function __construct(
        array $enum,
        bool $nullable,
        ?string $description
    ) {
        parent::__construct($nullable, $description);

        $this->enum = $enum;
        // assert count($enum) > 0
    }

    protected function getEnum(): array
    {
        return $this->enum;
    }
}
