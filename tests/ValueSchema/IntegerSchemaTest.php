<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\IntegerSchemaData;

class IntegerSchemaTest extends TestCase
{
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

    public function testEmptyEnum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Minimal one enum item is required');
        new IntegerSchemaData(enum: []);
    }

    public function testNonIntegerEnum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only integers are allowed for integer enum');
        // @phpstan-ignore argument.type (deliberately invalid input to test the runtime check)
        new IntegerSchemaData(enum: [1, '2']);
    }

    public function testNonListEnum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Integer enum must be a list of unique values');
        new IntegerSchemaData(enum: [
            1 => 1,
            2 => 2,
        ]);
    }

    public function testDuplicateEnum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Integer enum must be a list of unique values');
        new IntegerSchemaData(enum: [1, 2, 1]);
    }

    public function testExampleNotInEnum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value 3 for argument 'example'");
        new IntegerSchemaData(example: 3, enum: [1, 2]);
    }

    public function testEnumBelowMinimum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Enum value 0 violates argument 'minimum'");
        new IntegerSchemaData(minimum: 1, enum: [0, 1]);
    }

    public function testEnumEqualsExclusiveMinimum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Enum value 1 violates argument 'minimum'");
        new IntegerSchemaData(minimum: 1, exclusiveMinimum: true, enum: [1, 2]);
    }

    public function testEnumAboveMaximum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Enum value 3 violates argument 'maximum'");
        new IntegerSchemaData(maximum: 2, enum: [1, 3]);
    }

    public function testEnumEqualsExclusiveMaximum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Enum value 2 violates argument 'maximum'");
        new IntegerSchemaData(maximum: 2, exclusiveMaximum: true, enum: [1, 2]);
    }

    public function testEnumNotMultipleOf(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Enum value 3 violates argument 'multipleOf'");
        new IntegerSchemaData(multipleOf: 2, enum: [2, 3]);
    }

    public function testValidEnum(): void
    {
        $schema = new IntegerSchemaData(1, 10, false, true, 2, 4, enum: [2, 4, 8]);
        $this->assertSame([2, 4, 8], $schema->getEnum());
        $this->assertSame(4, $schema->getExample());
    }
}
