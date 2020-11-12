<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use LogicException;
use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\EnumSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\EnumSchema;

class EnumSchemaBuilderTest extends TestCase
{
    public function testMinimalBuild(): void
    {
        $builder = new EnumSchemaBuilder();
        $builder = $builder->withEnum(['a', 'b']);
        $this->assertEquals(new EnumSchema(['a', 'b']), $builder->build());
    }

    public function testFullBuild(): void
    {
        $builder = new EnumSchemaBuilder();
        $builder = $builder->withEnum(['a', 'b']);
        $builder = $builder->withDescription('enum');
        $builder = $builder->withNullable(true);
        $this->assertEquals(new EnumSchema(['a', 'b'], true, 'enum'), $builder->build());
    }

    public function testMissingData(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Property 'enum' isn't filled");
        $builder = new EnumSchemaBuilder();
        $builder->build();
    }
}
