<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

interface ArraySchema extends ValueSchemaInterface
{
    public function getMinItems(): ?int;

    public function getMaxItems(): ?int;

    public function getUniqueItems(): ?bool;

    public function getItemsSchema(): ValueSchemaInterface;
}
