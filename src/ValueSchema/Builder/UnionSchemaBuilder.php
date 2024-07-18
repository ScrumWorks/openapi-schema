<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\Data\UnionSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\UnionSchema;

final class UnionSchemaBuilder extends AbstractSchemaBuilder
{
    /**
     * @var AbstractSchemaBuilder[]
     */
    protected array $possibleSchemaBuilders;

    protected ?string $discriminatorPropertyName = null;

    /**
     * @param AbstractSchemaBuilder[] $possibleSchemaBuilders
     */
    public function __construct(array $possibleSchemaBuilders)
    {
        $this->possibleSchemaBuilders = $possibleSchemaBuilders;
    }

    /**
     * @param AbstractSchemaBuilder[] $possibleSchemaBuilders
     */
    public function withPossibleSchemaBuilders(array $possibleSchemaBuilders): self
    {
        $this->possibleSchemaBuilders = $possibleSchemaBuilders;
        return $this;
    }

    public function withDiscriminatorPropertyName(?string $discriminatorPropertyName): self
    {
        $this->discriminatorPropertyName = $discriminatorPropertyName;
        return $this;
    }

    /**
     * @return AbstractSchemaBuilder[]
     */
    public function getPossibleSchemaBuilders(): array
    {
        return $this->possibleSchemaBuilders;
    }

    public function getDiscriminatorPropertyName(): ?string
    {
        return $this->discriminatorPropertyName;
    }

    public function build(): UnionSchema
    {
        $possibleSchemas = \array_map(
            static fn (AbstractSchemaBuilder $builder) => $builder->build(),
            $this->possibleSchemaBuilders
        );

        return new UnionSchemaData(
            $possibleSchemas,
            $this->discriminatorPropertyName,
            $this->nullable,
            $this->description,
            $this->deprecated,
            $this->metaData,
        );
    }
}
