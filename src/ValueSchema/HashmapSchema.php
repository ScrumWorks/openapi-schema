<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

/**
 * @property-read ?ValueSchemaInterface $itemsSchema;
 */
class HashmapSchema extends AbstractValueSchema
{
    protected ?ValueSchemaInterface $itemsSchema;

    public function __construct(?ValueSchemaInterface $itemsSchema, bool $nullable, ?string $description)
    {
        parent::__construct($nullable, $description);

        $this->itemsSchema = $itemsSchema;
    }

    protected function getItemsSchema(): ?ValueSchemaInterface
    {
        return $this->itemsSchema;
    }
}
