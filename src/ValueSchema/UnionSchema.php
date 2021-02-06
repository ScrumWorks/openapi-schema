<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;

final class UnionSchema extends AbstractValueSchema
{
    /**
     * @var ValueSchemaInterface[]
     */
    private array $possibleSchemas;

    private ?string $discriminatorName;

    /**
     * @param ValueSchemaInterface[] $possibleSchemas
     */
    public function __construct(
        array $possibleSchemas,
        ?string $discriminatorName = null,
        bool $nullable = false,
        ?string $description = null
    ) {
        $this->possibleSchemas = $possibleSchemas;
        $this->discriminatorName = $discriminatorName;

        parent::__construct($nullable, $description);

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

    public function getDiscriminatorName(): ?string
    {
        return $this->discriminatorName;
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
            if ($this->discriminatorName !== null && ! $schema instanceof ObjectSchema) {
                throw new InvalidArgumentException('Discriminator can be specified only for object schemas.');
            }
        }
    }
}
