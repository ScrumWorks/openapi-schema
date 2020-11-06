<?php

declare(strict_types=1);

namespace Lang\OpenApiDefinition\ValueSchema\Builder;

use Lang\OpenApiDefinition\ValueSchema\EnumSchema;

/**
 * @method EnumSchema build()
 */
class EnumSchemaBuilder extends AbstractSchemaBuilder
{
    protected array $enum;

    /**
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
