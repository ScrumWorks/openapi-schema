<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

abstract class AbstractValueSchema implements ValueSchemaInterface
{
    protected bool $nullable;

    protected ?string $description;

    /**
     * @var ?mixed
     */
    protected $example;

    public function __construct(bool $nullable = false, ?string $description = null, $example = null)
    {
        $this->nullable = $nullable;
        $this->description = $description;
        $this->example = $example;
        $this->validate();
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return ?mixed
     */
    public function getExample()
    {
        return $this->example;
    }

    abstract protected function validate();
}
