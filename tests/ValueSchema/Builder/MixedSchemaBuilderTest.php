<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\MixedSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\MixedSchemaData;

class MixedSchemaBuilderTest extends TestCase
{
    public function testMinimalBuild(): void
    {
        $builder = new MixedSchemaBuilder();
        $this->assertEquals(new MixedSchemaData(), $builder->build());
    }

    public function testFullBuild(): void
    {
        $builder = new MixedSchemaBuilder();
        $builder = $builder->withDescription('boolean');
        $builder = $builder->withNullable(true);
        $builder = $builder->withDeprecated(true);
        $builder = $builder->withMetaData([
            'a' => 1,
        ]);

        $this->assertEquals(new MixedSchemaData(true, 'boolean', null, true, [
            'a' => 1,
        ]), $builder->build());
    }
}
