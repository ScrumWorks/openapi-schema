<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

use _HumbugBoxb854c950819b\Nette\Neon\Exception;

final class HashmapSchema extends AbstractValueSchema
{
    private ?ValueSchemaInterface $itemsSchema;

    /**
     * @var string[]
     */
    private array $requiredProperties;

    public function __construct(
        ?ValueSchemaInterface $itemsSchema,
        array $requiredProperties,
        bool $nullable,
        ?string $description
    ) {
        parent::__construct($nullable, $description);

        if ($requiredProperties && $itemsSchema === null) {
            throw new \Exception('TODO');
        }

        $this->itemsSchema = $itemsSchema;
        $this->requiredProperties = $requiredProperties;
    }

    public function getItemsSchema(): ?ValueSchemaInterface
    {
        return $this->itemsSchema;
    }

    /**
     * @return string[]
     */
    public function getRequiredProperties(): array
    {
        return $this->requiredProperties;
    }
}
