<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\EnumSchema;

/**
 * @method EnumSchema build()
 */
class EnumSchemaBuilder extends AbstractSchemaBuilder
{
    protected array $enum;

    /**
     * @param array<?string> $enum
     * @return static
     */
    public function withEnum(array $enum)
    {
        $this->enum = $enum;
        return $this;
    }

    protected function validate(): void
    {
        parent::validate();
        $this->assertRequiredProperty('enum');
    }

    protected function createInstance(): EnumSchema
    {
        return new EnumSchema(
            $this->enum,
            $this->nullable,
            $this->description
        );
    }
}