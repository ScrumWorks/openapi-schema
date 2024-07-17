<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

interface FloatSchema extends ValueSchemaInterface
{
    public function getMinimum(): ?float;

    public function getMaximum(): ?float;

    public function getExclusiveMinimum(): ?bool;

    public function getExclusiveMaximum(): ?bool;

    public function getMultipleOf(): ?float;

    public function getExample(): ?float;
}
