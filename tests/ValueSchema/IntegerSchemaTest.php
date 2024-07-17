<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\IntegerSchemaData;

class IntegerSchemaTest extends TestCase
{
    public function testInvalidMinimum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value -1 for argument 'minimum'");
        new IntegerSchemaData(-1);
    }

    public function testInvalidMaximum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value -1 for argument 'maximum'");
        new IntegerSchemaData(null, -1);
    }

    public function testExceedingMaximum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value 90 for argument 'maximum'");
        new IntegerSchemaData(100, 90);
    }

    public function testInvalidExclusiveMinimum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Can't set 'exclusiveMinimum' without 'minimum' argument");
        new IntegerSchemaData(null, null, true);
    }

    public function testInvalidExclusiveMaximum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Can't set 'exclusiveMaximum' without 'maximum' argument");
        new IntegerSchemaData(null, null, null, false);
    }
}
