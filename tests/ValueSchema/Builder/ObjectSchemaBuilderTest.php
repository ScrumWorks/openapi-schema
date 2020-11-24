<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ObjectSchemaBuilder;
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
        $builder = $builder->withPropertiesSchemas([
            'property' => new StringSchema(),
        ]);
        $builder = $builder->withRequiredProperties(['property']);
        $builder = $builder->withDescription('object');
        $builder = $builder->withNullable(true);
        $builder = $builder->withExample((object) [
            'property' => 'test',
            'c' => 'd',
        ]);
        $this->assertEquals(
            new ObjectSchema([
                'property' => new StringSchema(),
            ], ['property'], true, 'object', (object) [
                'property' => 'test',
                'c' => 'd',
            ]),
            $builder->build()
        );
    }
}
