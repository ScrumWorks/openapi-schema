<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\StringSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

final class StringSchemaBuilderTest extends TestCase
{
    public function testMinimalBuild(): void
    {
        $builder = new StringSchemaBuilder();
        $this->assertEquals(new StringSchema(), $builder->build());
    }

    public function testFullBuild(): void
    {
        $builder = new StringSchemaBuilder();
        $builder = $builder->withMinLength(10);
        $builder = $builder->withMaxLength(12);
        $builder = $builder->withFormat('email');
        $builder = $builder->withPattern('[a-z]+');
        $builder = $builder->withDescription('string');
        $builder = $builder->withNullable(true);
        $this->assertEquals(new StringSchema(10, 12, 'email', '[a-z]+', true, 'string'), $builder->build());
    }
}
