<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\HashmapSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\HashmapSchema;

final class HashmapSchemaBuilder extends AbstractSchemaBuilder
{
    protected ?AbstractSchemaBuilder $itemsSchemaBuilder = null;

    /**
     * @var string[]
     */
    protected array $requiredProperties = [];

    public function withItemsSchemaBuilder(?AbstractSchemaBuilder $itemsSchemaBuilder): self
    {
        $this->itemsSchemaBuilder = $itemsSchemaBuilder;
        return $this;
    }

    public function withRequiredProperties(array $requiredProperties): self
    {
        $this->requiredProperties = $requiredProperties;
        return $this;
    }

    public function getItemsSchemaBuilder(): ?AbstractSchemaBuilder
    {
        return $this->itemsSchemaBuilder;
    }

    /**
     * @return string[]
     */
    public function getRequiredProperties(): array
    {
        return $this->requiredProperties;
    }

    public function build(): HashmapSchema
    {
        if ($this->itemsSchemaBuilder === null) {
            throw new LogicException("'itemsSchemaBuilder' has to be set.");
        }

        try {
            $itemsSchema = $this->itemsSchemaBuilder->build();
        } catch (\Throwable $error) {
            throw new LogicException("items: {$error->getMessage()}", previous: $error);
        }

        return new HashmapSchemaData(
            $itemsSchema,
            $this->requiredProperties,
            $this->nullable,
            $this->description,
            $this->schemaName,
            $this->deprecated,
            $this->metaData,
        );
    }
}
