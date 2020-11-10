<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

final class EnumSchema extends AbstractValueSchema
{
    /**
     * @var array<?string>
     */
    private array $enum;

    /**
     * @param array<?string> $enum
     */
    public function __construct(array $enum, bool $nullable, ?string $description)
    {
        parent::__construct($nullable, $description);

        $this->enum = $enum;
        // assert count($enum) > 0
    }

    /**
     * @return array<?string>
     */
    public function getEnum(): array
    {
        return $this->enum;
    }
}
