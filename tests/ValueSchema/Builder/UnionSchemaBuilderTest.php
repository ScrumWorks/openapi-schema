<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\ObjectSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\UnionSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\UnionSchema;

class UnionSchemaBuilderTest extends TestCase
{
    public function testMinimalBuild(): void
    {
        $builder = new UnionSchemaBuilder([new ObjectSchemaBuilder()]);
        $this->assertEquals(new UnionSchema([new ObjectSchema([])]), $builder->build());
    }

    public function testFullBuild(): void
    {
        $builder = new UnionSchemaBuilder([new ObjectSchemaBuilder()]);
        $builder->withSchemaName('SchemaName');
        $builder->withDiscriminatorPropertyName('discriminatoR');
        $builder->withDescription('desc');
        $builder->withNullable(true);
        $builder->withDeprecated(true);

        $this->assertEquals(
            new UnionSchema([new ObjectSchema([])], 'discriminatoR', true, 'desc', true),
            $builder->build()
        );
    }
}
