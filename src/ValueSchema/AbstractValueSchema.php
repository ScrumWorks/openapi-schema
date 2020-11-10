<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

use Nette\SmartObject;

/**
 * @property-read bool $nullable
 * @property-read ?string $description
 */
abstract class AbstractValueSchema implements ValueSchemaInterface
{
    use SmartObject;

    protected bool $nullable;

    protected ?string $description;

    public function __construct(bool $nullable, ?string $description)
    {
        $this->nullable = $nullable;
        $this->description = $description;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
