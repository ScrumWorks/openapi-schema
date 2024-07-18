<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ArraySchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\StringSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\ArraySchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\StringSchemaData;

class ArraySchemaBuilderTest extends TestCase
{
    public function testMinimalBuild(): void
    {
        $builder = new ArraySchemaBuilder();
        $builder = $builder->withItemsSchemaBuilder(new StringSchemaBuilder());
        $this->assertEquals(new ArraySchemaData(new StringSchemaData()), $builder->build());
    }

    public function testFullBuild(): void
    {
        $builder = new ArraySchemaBuilder();
        $builder = $builder->withItemsSchemaBuilder(new StringSchemaBuilder());
        $builder = $builder->withMinItems(2);
        $builder = $builder->withMaxItems(3);
        $builder = $builder->withUniqueItems(true);
        $builder = $builder->withDescription('array');
        $builder = $builder->withNullable(true);
        $builder = $builder->withDeprecated(true);
        $builder = $builder->withMetaData([
            'a' => 1,
        ]);

        $this->assertEquals(
            new ArraySchemaData(new StringSchemaData(), 2, 3, true, true, 'array', null, true, [
                'a' => 1,
            ]),
            $builder->build()
        );
    }

    public function testMissingData(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("'itemsSchemaBuilder' has to be set.");
        $builder = new ArraySchemaBuilder();
        $builder->build();
    }
}
