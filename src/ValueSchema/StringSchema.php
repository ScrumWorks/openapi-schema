<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

interface StringSchema extends ValueSchemaInterface
{
    public function getMinLength(): ?int;

    public function getMaxLength(): ?int;

    public function getFormat(): ?string;

    public function getPattern(): ?string;

    public function getExample(): ?string;
}
