<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\HashmapSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\StringSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\HashmapSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

class HashmapSchemaBuilderTest extends TestCase
{
    public function testMinimalBuild(): void
    {
        $builder = new HashmapSchemaBuilder();
        $builder = $builder->withItemsSchemaBuilder(new StringSchemaBuilder());
        $this->assertEquals(new HashmapSchema(new StringSchema()), $builder->build());
    }

    public function testFullBuild(): void
    {
        $builder = new HashmapSchemaBuilder();
        $builder = $builder->withItemsSchemaBuilder(new StringSchemaBuilder());
        $builder = $builder->withRequiredProperties(['property']);
        $builder = $builder->withDescription('hashmap');
        $builder = $builder->withNullable(true);
        $this->assertEquals(
            new HashmapSchema(new StringSchema(), ['property'], true, 'hashmap'),
            $builder->build()
        );
    }

    public function testMissingData(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("'itemsSchemaBuilder' has to be set.");
        $builder = new HashmapSchemaBuilder();
        $builder->build();
    }
}
