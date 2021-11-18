<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\SchemaParser;

use Iterator;
use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\SchemaParserInterface;
use ScrumWorks\OpenApiSchema\Tests\DiTrait;
use ScrumWorks\OpenApiSchema\Tests\SchemaParser\Fixture\StringTypeWithFormat;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

final class SchemaParserTest extends TestCase
{
    use DiTrait;

    private SchemaParserInterface $schemaParser;

    protected function setUp(): void
    {
        $this->schemaParser = $this->getServiceFromContainerByType(SchemaParserInterface::class);
    }

    /**
     * @dataProvider provideData()
     */
    public function test(string $className, string $propertyName, string $expectedPropertySchema): void
    {
        $entitySchema = $this->schemaParser->getEntitySchema($className);
        $this->assertInstanceOf(ObjectSchema::class, $entitySchema);

        $propertySchema = $entitySchema->getPropertySchema($propertyName);
        $this->assertInstanceOf($expectedPropertySchema, $propertySchema);
    }

    public function provideData(): Iterator
    {
        yield [StringTypeWithFormat::class, 'date', StringSchema::class];
    }
}
