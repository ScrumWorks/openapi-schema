<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

// TOOD: maybe ComponentSchemaReference?
final class ReferenceSchema extends AbstractValueSchema
{
    private string $reference;

    public function __construct(string $reference)
    {
        parent::__construct(false, null, null);

        $this->reference = $reference;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    protected function validate(): void
    {
        // TODO: maybe chack reference is not empty
    }
}
