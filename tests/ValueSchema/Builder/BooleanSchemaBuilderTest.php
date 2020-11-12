<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\ValueSchema\BooleanSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\BooleanSchemaBuilder;

class BooleanSchemaBuilderTest extends TestCase
{
    public function testMinimalBuild(): void
    {
        $builder = new BooleanSchemaBuilder();
        $this->assertEquals(new BooleanSchema(), $builder->build());
    }

    public function testFullBuild(): void
    {
        $builder = new BooleanSchemaBuilder();
        $builder = $builder->withDescription('boolean');
        $builder = $builder->withNullable(true);
        $this->assertEquals(new BooleanSchema(true, 'boolean'), $builder->build());
    }
}
