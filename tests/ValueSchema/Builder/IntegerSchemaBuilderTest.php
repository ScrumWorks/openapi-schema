<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\IntegerSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema;

class IntegerSchemaBuilderTest extends TestCase
{
    public function testMinimalBuild(): void
    {
        $builder = new IntegerSchemaBuilder();
        $this->assertEquals(new IntegerSchema(), $builder->build());
    }

    public function testFullBuild(): void
    {
        $builder = new IntegerSchemaBuilder();
        $builder = $builder->withMinimum(0);
        $builder = $builder->withMaximum(10);
        $builder = $builder->withExclusiveMinimum(true);
        $builder = $builder->withExclusiveMaximum(false);
        $builder = $builder->withMultipleOf(2);
        $builder = $builder->withExample(8);
        $builder = $builder->withDescription('integer');
        $builder = $builder->withNullable(true);
        $builder = $builder->withDeprecated(true);

        $this->assertEquals(
            new IntegerSchema(
                minimum: 0,
                maximum: 10,
                exclusiveMinimum: true,
                exclusiveMaximum: false,
                multipleOf: 2,
                example: 8,
                nullable: true,
                description: 'integer',
                schemaName: null,
                isDeprecated: true,
            ),
            $builder->build(),
        );
    }
}
