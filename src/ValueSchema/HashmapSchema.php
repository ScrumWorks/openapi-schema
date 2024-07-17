<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

interface HashmapSchema extends ValueSchemaInterface
{
    public function getItemsSchema(): ValueSchemaInterface;

    /**
     * @return string[]
     */
    public function getRequiredProperties(): array;
}
