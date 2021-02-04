<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ObjectSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\StringSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

class ObjectSchemaBuilderTest extends TestCase
{
    public function testMinimalBuild(): void
    {
        $builder = new ObjectSchemaBuilder();
        $this->assertEquals(new ObjectSchema([]), $builder->build());
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
        $this->assertEquals(
            new ObjectSchema([
                'property' => new StringSchema(),
            ], ['property'], true, 'object'),
            $builder->build()
        );
    }
}
