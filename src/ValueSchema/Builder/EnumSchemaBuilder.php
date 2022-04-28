<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\EnumSchema;

final class EnumSchemaBuilder extends AbstractSchemaBuilder
{
    /**
     * @var string[]|null
     */
    protected ?array $enum = null;

    /**
     * @param string[] $enum
     */
    public function withEnum(array $enum): self
    {
        $this->enum = $enum;
        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getEnum(): ?array
    {
        return $this->enum;
    }

    public function build(): EnumSchema
    {
        if ($this->enum === null) {
            throw new LogicException('Enum has to be set.');
        }

        return new EnumSchema($this->enum, $this->nullable, $this->description, $this->schemaName, $this->deprecated);
    }
}
