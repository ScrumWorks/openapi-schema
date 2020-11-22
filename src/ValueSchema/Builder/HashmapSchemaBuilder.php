<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\HashmapSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

final class HashmapSchemaBuilder extends AbstractSchemaBuilder
{
    protected ?AbstractSchemaBuilder $itemsSchemaBuilder = null;

    protected ?ValueSchemaInterface $itemsSchema = null;

    /**
     * @var string[]
     */
    protected array $requiredProperties = [];

    /**
     * @return static
     */
    public function withItemsSchema(?ValueSchemaInterface $itemsSchema)
    {
        $this->itemsSchema = $itemsSchema;
        return $this;
    }

    /**
     * @return static
     */
    public function withItemsSchemaBuilder(?AbstractSchemaBuilder $itemsSchemaBuilder)
    {
        $this->itemsSchemaBuilder = $itemsSchemaBuilder;
        return $this;
    }

    /**
     * @return static
     */
    public function withRequiredProperties(array $requiredProperties)
    {
        $this->requiredProperties = $requiredProperties;
        return $this;
    }

    public function getItemsSchema(): ?ValueSchemaInterface
    {
        return $this->itemsSchema;
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
        if ($this->itemsSchema === null && $this->itemsSchemaBuilder === null) {
            throw new LogicException("One of `itemsSchema` or 'itemsSchemaBuilder' has to be set.");
        }

        if ($this->itemsSchema !== null && $this->itemsSchemaBuilder !== null) {
            throw new LogicException("Only one of `itemsSchema` or 'itemsSchemaBuilder' has to be set.");
        }

        return new HashmapSchema(
            $this->itemsSchema ?? $this->itemsSchemaBuilder->build(),
            $this->requiredProperties,
            $this->nullable,
            $this->description
        );
    }
}
