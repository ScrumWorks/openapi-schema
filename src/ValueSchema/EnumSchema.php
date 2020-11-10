<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

/**
 * @property-read string[] $enum
 */
class EnumSchema extends AbstractValueSchema
{
    protected array $enum;

    /**
     * @param array<?string> $enum
     */
    public function __construct(array $enum, bool $nullable, ?string $description)
    {
        parent::__construct($nullable, $description);

        $this->enum = $enum;
        // assert count($enum) > 0
    }

    protected function getEnum(): array
    {
        return $this->enum;
    }
}
