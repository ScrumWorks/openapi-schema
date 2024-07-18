<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Data;

use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\UnionSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

final class UnionSchemaData extends AbstractValueSchema implements UnionSchema
{
    /**
     * @param ValueSchemaInterface[] $possibleSchemas
     * @param array<string, mixed> $metaData
     */
    public function __construct(
        private readonly array $possibleSchemas,
        private readonly ?string $discriminatorPropertyName = null,
        bool $nullable = false,
        ?string $description = null,
        bool $isDeprecated = false,
        array $metaData = [],
    ) {
        parent::__construct($nullable, $description, null, $isDeprecated, $metaData);

        $this->nullable = $this->nullable || \array_reduce(
            $possibleSchemas,
            static fn (bool $carry, ValueSchemaInterface $type) => $carry || $type->isNullable(),
            false
        );
    }

    /**
     * @return ValueSchemaInterface[]
     */
    public function getPossibleSchemas(): array
    {
        return $this->possibleSchemas;
    }

    public function getDiscriminatorPropertyName(): ?string
    {
        return $this->discriminatorPropertyName;
    }

    protected function validate(): void
    {
        if (\count($this->possibleSchemas) < 1) {
            throw new InvalidArgumentException('At least one possible schema needed.');
        }

        foreach ($this->possibleSchemas as $schema) {
            if (! ($schema instanceof ValueSchemaInterface)) {
                throw new InvalidArgumentException(\sprintf(
                    'Invalid schema (must be instance of %s)',
                    ValueSchemaInterface::class
                ));
            }
            if ($this->discriminatorPropertyName !== null && ! $schema instanceof ObjectSchema) {
                throw new InvalidArgumentException('Discriminator can be specified only for object schemas.');
            }
        }
    }
}
