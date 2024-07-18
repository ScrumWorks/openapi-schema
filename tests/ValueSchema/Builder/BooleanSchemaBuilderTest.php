<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\BooleanSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\BooleanSchemaData;

class BooleanSchemaBuilderTest extends TestCase
{
    public function testMinimalBuild(): void
    {
        $builder = new BooleanSchemaBuilder();
        $this->assertEquals(new BooleanSchemaData(), $builder->build());
    }

    public function testFullBuild(): void
    {
        $builder = new BooleanSchemaBuilder();
        $builder = $builder->withDescription('boolean');
        $builder = $builder->withNullable(true);
        $builder = $builder->withDeprecated(true);
        $builder = $builder->withMetaData([
            'a' => 1,
        ]);

        $this->assertEquals(new BooleanSchemaData(true, 'boolean', null, true, [
            'a' => 1,
        ]), $builder->build());
    }
}
