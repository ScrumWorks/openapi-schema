<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\MixedSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\MixedSchema;

class MixedSchemaBuilderTest extends TestCase
{
    public function testMinimalBuild(): void
    {
        $builder = new MixedSchemaBuilder();
        $this->assertEquals(new MixedSchema(), $builder->build());
    }

    public function testFullBuild(): void
    {
        $builder = new MixedSchemaBuilder();
        $builder = $builder->withDescription('boolean');
        $builder = $builder->withNullable(true);
        $builder = $builder->withExample(true);
        $this->assertEquals(new MixedSchema(true, 'boolean', true), $builder->build());
    }
}
