<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema\Data;

use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema;

final class IntegerSchemaData extends AbstractValueSchema implements IntegerSchema
{
    /**
     * @param array<string, mixed> $metaData
     * @param int[]|null $enum
     */
    public function __construct(
        private readonly ?int $minimum = null,
        private readonly ?int $maximum = null,
        private readonly ?bool $exclusiveMinimum = null,
        private readonly ?bool $exclusiveMaximum = null,
        private readonly ?int $multipleOf = null,
        private readonly ?int $example = null,
        bool $nullable = false,
        ?string $description = null,
        ?string $schemaName = null,
        bool $isDeprecated = false,
        array $metaData = [],
        private readonly ?array $enum = null,
    ) {
        parent::__construct($nullable, $description, $schemaName, $isDeprecated, $metaData);
    }

    public function getMinimum(): ?int
    {
        return $this->minimum;
    }

    public function getMaximum(): ?int
    {
        return $this->maximum;
    }

    public function getExclusiveMinimum(): ?bool
    {
        return $this->exclusiveMinimum;
    }

    public function getExclusiveMaximum(): ?bool
    {
        return $this->exclusiveMaximum;
    }

    public function getMultipleOf(): ?int
    {
        return $this->multipleOf;
    }

    public function getExample(): ?int
    {
        return $this->example;
    }

    /**
     * @return int[]|null
     */
    public function getEnum(): ?array
    {
        return $this->enum;
    }

    protected function validate(): void
    {
        if ($this->minimum !== null && $this->maximum !== null && $this->maximum < $this->minimum) {
            throw new InvalidArgumentException(\sprintf("Invalid value %d for argument 'maximum'", $this->maximum));
        }
        if ($this->minimum === null && $this->exclusiveMinimum !== null) {
            throw new InvalidArgumentException("Can't set 'exclusiveMinimum' without 'minimum' argument");
        }
        if ($this->maximum === null && $this->exclusiveMaximum !== null) {
            throw new InvalidArgumentException("Can't set 'exclusiveMaximum' without 'maximum' argument");
        }
        if ($this->enum !== null) {
            if (! $this->enum) {
                throw new InvalidArgumentException('Minimal one enum item is required');
            }
            foreach ($this->enum as $enum) {
                if (! \is_int($enum)) {
                    throw new InvalidArgumentException('Only integers are allowed for integer enum');
                }
                if ($this->minimum !== null && ($this->exclusiveMinimum ? $enum <= $this->minimum : $enum < $this->minimum)) {
                    throw new InvalidArgumentException(\sprintf("Enum value %d violates argument 'minimum'", $enum));
                }
                if ($this->maximum !== null && ($this->exclusiveMaximum ? $enum >= $this->maximum : $enum > $this->maximum)) {
                    throw new InvalidArgumentException(\sprintf("Enum value %d violates argument 'maximum'", $enum));
                }
                if ($this->multipleOf !== null && $enum % $this->multipleOf !== 0) {
                    throw new InvalidArgumentException(\sprintf("Enum value %d violates argument 'multipleOf'", $enum));
                }
            }
            if (! \array_is_list($this->enum) || \count(\array_unique($this->enum)) !== \count($this->enum)) {
                throw new InvalidArgumentException('Integer enum must be a list of unique values');
            }
            if ($this->example !== null && ! \in_array($this->example, $this->enum, true)) {
                throw new InvalidArgumentException(\sprintf("Invalid value %d for argument 'example'", $this->example));
            }
        }
    }
}
