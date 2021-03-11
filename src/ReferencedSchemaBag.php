<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema;

use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

class ReferencedSchemaBag
{
    /**
     * @var array<string, ValueSchemaInterface>
     */
    private array $referencedSchemas;

    public function __construct(array $referencedSchemas)
    {
        $this->referencedSchemas = $referencedSchemas;
    }

    public function getSchema($reference): ValueSchemaInterface
    {
        if (! isset($this->referencedSchemas[$reference])) {
            // throw
        }

        return $this->referencedSchemas[$reference];
    }
}
