<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\EnumSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\EnumSchemaData;

class EnumSchemaBuilderTest extends TestCase
{
    public function testMinimalBuild(): void
    {
        $builder = new EnumSchemaBuilder();
        $builder = $builder->withEnum(['a', 'b']);
        $this->assertEquals(new EnumSchemaData(['a', 'b']), $builder->build());
    }

    public function testFullBuild(): void
    {
        $builder = new EnumSchemaBuilder();
        $builder = $builder->withEnum(['a', 'b']);
        $builder = $builder->withDescription('enum');
        $builder = $builder->withNullable(true);
        $builder = $builder->withDeprecated(true);

        $this->assertEquals(new EnumSchemaData(['a', 'b'], true, 'enum', null, true), $builder->build());
    }

    public function testMissingData(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum has to be set.');
        $builder = new EnumSchemaBuilder();
        $builder->build();
    }
}
