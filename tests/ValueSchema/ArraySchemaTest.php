<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\ArraySchema;
use ScrumWorks\OpenApiSchema\ValueSchema\MixedSchema;

class ArraySchemaTest extends TestCase
{
    public function testInvalidMinItems(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value -1 for argument 'minItems'");
        new ArraySchema(new MixedSchema(), -1);
    }

    public function testInvalidMaxItems(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value -1 for argument 'maxItems'");
        new ArraySchema(new MixedSchema(), null, -1);
    }

    public function testExceedingMaxItems(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value 9 for argument 'maxItems'");
        new ArraySchema(new MixedSchema(), 10, 9);
    }
}
