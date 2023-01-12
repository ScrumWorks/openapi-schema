<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\SchemaParser\Fixture;

use ScrumWorks\OpenApiSchema\Attribute as OA;

#[OA\ComponentSchema(schemaName: 'subEntity')]
class TestSubEntity
{
    #[OA\IntegerValue(minimum: 25)]
    #[OA\Property(description: 'sub...')]
    public int $subInteger;
}
