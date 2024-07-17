<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\BooleanSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\UnionSchemaData;

class UnionSchemaTest extends TestCase
{
    public function testNoPossibleSchemas(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one possible schema needed.');
        new UnionSchemaData([]);
    }

    public function testInvalidPossibleSchemaType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            "Invalid schema (must be instance of ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface)"
        );
        // @phpstan-ignore-next-line
        new UnionSchemaData([-1]);
    }

    public function testInvalidSchemaWithDiscriminator(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Discriminator can be specified only for object schemas.');
        new UnionSchemaData([new BooleanSchemaData()], 'dis');
    }

    #[DataProvider(methodName: 'dpTestNullable')]
    public function testNullable(bool $isPossibleSchemaNullable, bool $isUnionSchemaNullable, bool $result): void
    {
        $schema = new UnionSchemaData([new BooleanSchemaData($isPossibleSchemaNullable)], null, $isUnionSchemaNullable);
        $this->assertSame($result, $schema->isNullable());
    }

    public static function dpTestNullable(): Iterator
    {
        yield [true, true, true];
        yield [true, false, true];
        yield [false, true, true];
        yield [false, false, false];
    }
}
