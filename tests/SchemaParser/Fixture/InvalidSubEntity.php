<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\SchemaParser\Fixture;

use ScrumWorks\OpenApiSchema\Attribute as OA;

class InvalidSubEntity
{
    #[OA\EnumValue(enum: [])]
    public string $emptyEnum;
}
