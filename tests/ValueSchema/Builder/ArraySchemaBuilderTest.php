<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\ArraySchema;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ArraySchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

class ArraySchemaBuilderTest extends TestCase
{
    public function testMinimalBuild(): void
    {
        $builder = new ArraySchemaBuilder();
        $builder = $builder->withItemsSchema(new StringSchema());
        $this->assertEquals(new ArraySchema(new StringSchema()), $builder->build());
    }

    public function testFullBuild(): void
    {
        $builder = new ArraySchemaBuilder();
        $builder = $builder->withItemsSchema(new StringSchema());
        $builder = $builder->withMinItems(2);
        $builder = $builder->withMaxItems(3);
        $builder = $builder->withUniqueItems(true);
        $builder = $builder->withDescription('array');
        $builder = $builder->withNullable(true);
        $this->assertEquals(new ArraySchema(new StringSchema(), 2, 3, true, true, 'array'), $builder->build());
    }

    public function testMissingData(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Property 'itemsSchema' isn't filled");
        $builder = new ArraySchemaBuilder();
        $builder->build();
    }
}
