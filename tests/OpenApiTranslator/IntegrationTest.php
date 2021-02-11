<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\OpenApiTranslator;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Annotation as OA;
use ScrumWorks\OpenApiSchema\DiContainer;
use ScrumWorks\OpenApiSchema\OpenApiTranslatorInterface;
use ScrumWorks\OpenApiSchema\SchemaCollection\ClassSchemaCollection;
use ScrumWorks\OpenApiSchema\SchemaParserInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\Reference;

/**
 * @OA\ComponentSchema(schemaName="SubA")
 */
class SubA
{
    public string $type;
}

/**
 * @OA\ComponentSchema(schemaName="SubB")
 */
class SubB
{
    public string $type;
}

class Testing
{
    /**
     * @var SubA|SubB
     *
     * @OA\Union(discriminator="type", mapping={ "a": "SubA", "b": "SubB" })
     */
    public $union;
}

class IntegrationTest extends TestCase
{
    protected SchemaParserInterface $schemaParser;

    protected ClassSchemaCollection $classSchemaCollection;

    protected OpenApiTranslatorInterface $openApiTranslator;

    protected function setUp(): void
    {
        $diContainer = new DiContainer();

        $this->schemaParser = $diContainer->getSchemaParser();
        $this->openApiTranslator = $diContainer->getOpenApiTranslator();
        $this->classSchemaCollection = new ClassSchemaCollection('#/components/schemas');
    }

    public function testUnion(): void
    {
        /** @var Reference $entityReferenceSchema */
        $entityReferenceSchema = $this->schemaParser->getEntitySchema(Testing::class, $this->classSchemaCollection);
        $this->assertSame(
            [
                '$ref' => '#/components/schemas/ScrumWorks-OpenApiSchema-Tests-OpenApiTranslator-Testing',
            ],
            $this->openApiTranslator->translateValueSchema($entityReferenceSchema)
        );

        $this->assertSame(
            [
                'type' => 'object',
                'properties' => [
                    'union' => [
                        'oneOf' => [
                            [
                                '$ref' => '#/components/schemas/SubA',
                            ],
                            [
                                '$ref' => '#/components/schemas/SubB',
                            ],
                        ],
                        'discriminator' => [
                            'propertyName' => 'type',
                            'mapping' => [
                                'a' => '#/components/schemas/SubA',
                                'b' => '#/components/schemas/SubB',
                            ],
                        ],
                    ],
                ],
            ],
            $this->openApiTranslator->translateValueSchema(
                $this->classSchemaCollection->getSchemaForReference(
                    '#/components/schemas/ScrumWorks-OpenApiSchema-Tests-OpenApiTranslator-Testing'
                )
            )
        );
    }
}
