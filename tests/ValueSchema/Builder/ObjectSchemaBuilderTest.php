<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ObjectSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\StringSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\ObjectSchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\StringSchemaData;

class ObjectSchemaBuilderTest extends TestCase
{
    public function testMinimalBuild(): void
    {
        $builder = new ObjectSchemaBuilder();
        $this->assertEquals(new ObjectSchemaData([]), $builder->build());
    }

    public function testFullBuild(): void
    {
        $builder = new ObjectSchemaBuilder();
        $builder = $builder->withPropertiesSchemaBuilders([
            'property' => new StringSchemaBuilder(),
        ]);
        $builder = $builder->withRequiredProperties(['property']);
        $builder = $builder->withDescription('object');
        $builder = $builder->withNullable(true);
        $builder = $builder->withDeprecated(true);

        $this->assertEquals(
            new ObjectSchemaData([
                'property' => new StringSchemaData(),
            ], ['property'], true, 'object', null, true),
            $builder->build()
        );
    }
}
