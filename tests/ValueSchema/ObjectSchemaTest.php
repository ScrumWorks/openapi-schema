<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\ObjectSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\StringSchemaData;

class ObjectSchemaTest extends TestCase
{
    public function testBadParameterKey(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Property key '1' must be string");
        // @phpstan-ignore-next-line
        new ObjectSchemaData([
            1 => new StringSchemaData(),
        ]);
    }

    public function testBadParameterValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            "Invalid schema (must be instance of ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface)"
        );
        // @phpstan-ignore-next-line
        new ObjectSchemaData([
            'test' => 'test',
        ]);
    }

    public function testBadRequiredParameters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Required properties are not listed in schema (int, bool)');
        new ObjectSchemaData(
            [
                'string' => new StringSchemaData(),
            ],
            ['string', 'int', 'bool']
        );
    }

    public function testNotExistsProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Property 'not-exists' doesn't exists");
        $schema = new ObjectSchemaData([]);
        $schema->getPropertySchema('not-exists');
    }
}
