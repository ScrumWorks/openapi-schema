<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\HashmapSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\StringSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\HashmapSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\StringSchemaData;

class HashmapSchemaBuilderTest extends TestCase
{
    public function testMinimalBuild(): void
    {
        $builder = new HashmapSchemaBuilder();
        $builder = $builder->withItemsSchemaBuilder(new StringSchemaBuilder());
        $this->assertEquals(new HashmapSchemaData(new StringSchemaData()), $builder->build());
    }

    public function testFullBuild(): void
    {
        $builder = new HashmapSchemaBuilder();
        $builder = $builder->withItemsSchemaBuilder(new StringSchemaBuilder());
        $builder = $builder->withRequiredProperties(['property']);
        $builder = $builder->withDescription('hashmap');
        $builder = $builder->withNullable(true);
        $builder = $builder->withDeprecated(true);

        $this->assertEquals(
            new HashmapSchemaData(new StringSchemaData(), ['property'], true, 'hashmap', null, true),
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
