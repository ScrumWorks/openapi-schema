<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema\Builder;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\StringSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

class StringSchemaBuilderTest extends TestCase
{
    public function testMinimalBuild(): void
    {
        $builder = new StringSchemaBuilder();
        $this->assertEquals(new StringSchema(), $builder->build());
    }

    public function testFullBuild(): void
    {
        $builder = new StringSchemaBuilder();
        $builder = $builder->withMinLength(3);
        $builder = $builder->withMaxLength(12);
        $builder = $builder->withFormat('email');
        $builder = $builder->withPattern('[a-z]+');
        $builder = $builder->withExample('example');
        $builder = $builder->withDescription('string');
        $builder = $builder->withNullable(true);
        $builder = $builder->withDeprecated(true);

        $this->assertEquals(
            new StringSchema(
                minLength: 3,
                maxLength: 12,
                format: 'email',
                pattern: '[a-z]+',
                example: 'example',
                nullable: true,
                description: 'string',
                schemaName: null,
                isDeprecated: true,
            ),
            $builder->build(),
        );
    }
}
