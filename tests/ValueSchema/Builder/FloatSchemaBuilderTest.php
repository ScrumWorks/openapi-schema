<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\FloatSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\FloatSchema;

class FloatSchemaBuilderTest extends TestCase
{
    public function testMinimalBuild(): void
    {
        $builder = new FloatSchemaBuilder();
        $this->assertEquals(new FloatSchema(), $builder->build());
    }

    public function testFullBuild(): void
    {
        $builder = new FloatSchemaBuilder();
        $builder = $builder->withMinimum(1.1);
        $builder = $builder->withMaximum(2.2);
        $builder = $builder->withExclusiveMinimum(true);
        $builder = $builder->withExclusiveMaximum(false);
        $builder = $builder->withMultipleOf(0.1);
        $builder = $builder->withExample(1.9);
        $builder = $builder->withDescription('float');
        $builder = $builder->withNullable(true);
        $builder = $builder->withDeprecated(true);

        $this->assertEquals(
            new FloatSchema(
                minimum: 1.1,
                maximum: 2.2,
                exclusiveMinimum: true,
                exclusiveMaximum: false,
                multipleOf: 0.1,
                example: 1.9,
                nullable: true,
                description: 'float',
                schemaName: null,
                isDeprecated: true,
            ),
            $builder->build(),
        );
    }
}
