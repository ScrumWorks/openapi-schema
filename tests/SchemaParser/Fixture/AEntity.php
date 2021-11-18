<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\SchemaParser\Fixture;

use ScrumWorks\OpenApiSchema\Annotation as OA;

/**
 * @OA\ComponentSchema(schemaName="AEnt")
 */
class AEntity
{
    public string $type;
}
