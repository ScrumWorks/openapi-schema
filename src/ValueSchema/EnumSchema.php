<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

final class EnumSchema extends AbstractValueSchema
{
    /**
     * @var string[]
     */
    private array $enum;

    /**
     * @param string[] $enum
     */
    public function __construct(array $enum, bool $nullable = false, ?string $description = null)
    {
        parent::__construct($nullable, $description);

        $this->enum = $enum;
        // assert count($enum) > 0
    }

    /**
     * @return string[]
     */
    public function getEnum(): array
    {
        return $this->enum;
    }
}
