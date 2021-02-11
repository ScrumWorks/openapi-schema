<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\ValueSchema;

final class Reference extends AbstractValueSchema
{
    private string $referencePath;

    public function __construct(string $referencePath)
    {
        $this->referencePath = $referencePath;

        parent::__construct(false, null, null);
    }

    public function getReferencePath(): string
    {
        return $this->referencePath;
    }

    protected function validate(): void
    {
    }
}
