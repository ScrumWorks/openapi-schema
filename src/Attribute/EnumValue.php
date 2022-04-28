<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
final class EnumValue implements ValueInterface
{
    /**
     * @param string[] $enum
     */
    public function __construct(
        private readonly array $enum,
    ) {
    }

    /**
     * @return string[]
     */
    public function getEnum(): array
    {
        return $this->enum;
    }
}
