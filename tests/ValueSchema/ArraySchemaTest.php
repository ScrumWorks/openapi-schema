<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\ArraySchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\MixedSchemaData;

class ArraySchemaTest extends TestCase
{
    public function testInvalidMinItems(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value -1 for argument 'minItems'");
        new ArraySchemaData(new MixedSchemaData(), -1);
    }

    public function testInvalidMaxItems(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value -1 for argument 'maxItems'");
        new ArraySchemaData(new MixedSchemaData(), null, -1);
    }

    public function testExceedingMaxItems(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value 9 for argument 'maxItems'");
        new ArraySchemaData(new MixedSchemaData(), 10, 9);
    }
}
