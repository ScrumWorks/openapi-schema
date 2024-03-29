<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\SchemaParser\Fixture;

use ScrumWorks\OpenApiSchema\Attribute as OA;

#[OA\ComponentSchema(schemaName: 'BEnt')]
class BEntity
{
    public string $type;
}
