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
        $builder = $builder->withDescription('float');
        $builder = $builder->withNullable(true);
        $this->assertEquals(new FloatSchema(1.1, 2.2, true, false, 0.1, true, 'float'), $builder->build());
    }
}
