<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Builder;

use ScrumWorks\OpenApiSchema\ValueSchema\UnionSchema;

final class UnionSchemaBuilder extends AbstractSchemaBuilder
{
    /**
     * @var AbstractSchemaBuilder[]
     */
    protected array $possibleSchemaBuilders;

    protected ?string $discriminatorName = null;

    /**
     * @param AbstractSchemaBuilder[] $possibleSchemaBuilders
     */
    public function __construct(array $possibleSchemaBuilders)
    {
        $this->possibleSchemaBuilders = $possibleSchemaBuilders;
    }

    /**
     * @param AbstractSchemaBuilder[] $possibleSchemaBuilders
     * @return static
     */
    public function withPossibleSchemaBuilders(array $possibleSchemaBuilders)
    {
        $this->possibleSchemaBuilders = $possibleSchemaBuilders;
        return $this;
    }

    /**
     * @return $this
     */
    public function withDiscriminatorName(?string $discriminatorName)
    {
        $this->discriminatorName = $discriminatorName;
        return $this;
    }

    /**
     * @return AbstractSchemaBuilder[]
     */
    public function getPossibleSchemaBuilders(): array
    {
        return $this->possibleSchemaBuilders;
    }

    public function getDiscriminatorName(): ?string
    {
        return $this->discriminatorName;
    }

    public function build(): UnionSchema
    {
        $possibleSchemas = \array_map(
            static fn (AbstractSchemaBuilder $builder) => $builder->build(),
            $this->possibleSchemaBuilders
        );

        return new UnionSchema($possibleSchemas, $this->discriminatorName, $this->nullable, $this->description);
    }
}
