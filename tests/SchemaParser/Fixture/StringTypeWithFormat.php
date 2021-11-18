<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\SchemaParser\Fixture;

use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\Annotation\ArrayValue;

final class StringTypeWithFormat
{
    /**
     * @OA\StringValue(format="date")
     * @var string
     */
    public $date;

    /**
     * @OA\ArrayValue(minItems=4)
     */
    public array $minItems;
}
