<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\SchemaParser;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\SchemaParserInterface;
use ScrumWorks\OpenApiSchema\Tests\DiTrait;
use ScrumWorks\OpenApiSchema\Tests\SchemaParser\Fixture\StringTypeWithFormat;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

final class SchemaParserTest extends TestCase
{
    use DiTrait;

    private SchemaParserInterface $schemaParser;

    protected function setUp(): void
    {
        $this->schemaParser = $this->getServiceFromContainerByType(SchemaParserInterface::class);
    }

    public function test(): void
    {
        $entitySchema = $this->schemaParser->getEntitySchema(StringTypeWithFormat::class);

        $this->assertInstanceOf(ValueSchemaInterface::class, $entitySchema);
        $this->assertInstanceOf(ObjectSchema::class, $entitySchema);

        $datePropertySchema = $entitySchema->getPropertySchema('date');
        $this->assertInstanceOf(StringSchema::class, $datePropertySchema);
    }
}
