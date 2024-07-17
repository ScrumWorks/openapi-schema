<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

interface IntegerSchema extends ValueSchemaInterface
{
    public function getMinimum(): ?int;

    public function getMaximum(): ?int;

    public function getExclusiveMinimum(): ?bool;

    public function getExclusiveMaximum(): ?bool;

    public function getMultipleOf(): ?int;

    public function getExample(): ?int;
}
