<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\SchemaParser\Fixture;

use ScrumWorks\OpenApiSchema\Annotation as OA;

final class StringTypeWithFormat
{
    /**
     * @OA\StringValue(format="date")
     * @var string
     */
    public $date;
}
