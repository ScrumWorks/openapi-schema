<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

class ObjectSchemaTest extends TestCase
{
    public function testBadParameterKey(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Property key '1' must be string");
        // @phpstan-ignore-next-line
        new ObjectSchema([
            1 => new StringSchema(),
        ]);
    }

    public function testBadParameterValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            "Invalid schema (must be instance of ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface)"
        );
        // @phpstan-ignore-next-line
        new ObjectSchema([
            'test' => 'test',
        ]);
    }

    public function testBadRequiredParameters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Required properties are not listed in schema (int, bool)');
        new ObjectSchema(
            [
                'string' => new StringSchema(),
            ],
            ['string', 'int', 'bool']
        );
    }

    public function testNotExistsProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Property 'not-exists' doesn't exists");
        $schema = new ObjectSchema([]);
        $schema->getPropertySchema('not-exists');
    }
}
