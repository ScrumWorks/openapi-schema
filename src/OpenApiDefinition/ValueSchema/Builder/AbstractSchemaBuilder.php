<?php

declare(strict_types=1);

namespace Lang\OpenApiDefinition\ValueSchema\Builder;

use Lang\OpenApiDefinition\ValueSchema\ValueSchemaInterface;
use Nette\SmartObject;

abstract class AbstractSchemaBuilder
{
    use SmartObject;

    protected bool $nullable = false;
    protected ?string $description = null;

    /**
     * @return static
     */
    final public function withNullable(bool $nullable)
    {
        $this->nullable = $nullable;
        return $this;
    }

    /**
     * @return static
     */
    final public function withDescription(?string $description)
    {
        $this->description = $description;
        return $this;
    }

    public function build(): ValueSchemaInterface
    {
        $this->validate();
        return $this->createInstance();
    }

    protected function assertRequiredProperty(string $property): void
    {
        try {
            $this->{$property};
        } catch (\Error $e) {
            // TODO: make our own Exception
            throw new \Exception(sprintf("Property '%s' isn't filled", $property));
        }
    }

    protected function validate(): void
    {
    }

    abstract protected function createInstance(): ValueSchemaInterface;
}
