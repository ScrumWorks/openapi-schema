<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\Validation;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\SchemaBuilder\SchemaBuilderDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\SchemaBuilderFactory;
use ScrumWorks\OpenApiSchema\SchemaCollection\ClassSchemaCollection;
use ScrumWorks\OpenApiSchema\SchemaParser;
use ScrumWorks\OpenApiSchema\SchemaParserInterface;
use ScrumWorks\OpenApiSchema\Tests\Validation\_Support\CreateValidatorTrait;
use ScrumWorks\OpenApiSchema\Tests\Validation\_Support\TestEntity;
use ScrumWorks\PropertyReader\PropertyTypeReader;
use ScrumWorks\PropertyReader\VariableTypeUnifyService;

class IntegrationTest extends TestCase
{
    use CreateValidatorTrait;

    public function testValid(): void
    {
        $data = \json_decode('
            {
                "cislo":123,
                "retezec":"hello",
                "objekt":{
                    "flag":true,
                    "pole":[1,2.0,3],
                    "hash":{
                        "1":"str1",
                        "5":"str5"
                    }
                }
            }
        ');

        $schemaParser = $this->createSchemaParser();
        $validator = $this->createValueValidator();

        $classSchemaCollection = $this->createClassSchemaCollection();
        $schema = $schemaParser->getEntitySchema(TestEntity::class, $classSchemaCollection);
        $validationResult = $validator->validate($schema, $classSchemaCollection, $data);

        $this->assertTrue($validationResult->isValid());
        $this->assertSame([], $validationResult->getViolations());
    }

    public function testInvalid(): void
    {
        $data = \json_decode('
            {
                "cislo":123,
                "retezec":"hello",
                "objekt":{
                    "flag":null,
                    "pole":[1,true,3],
                    "hash":{
                        "1":"str1",
                        "5":1.0
                    }
                }
            }
        ');

        $schemaParser = $this->createSchemaParser();
        $validator = $this->createValueValidator();

        $classSchemaCollection = $this->createClassSchemaCollection();
        $schema = $schemaParser->getEntitySchema(TestEntity::class, $classSchemaCollection);
        $validationResult = $validator->validate($schema, $classSchemaCollection, $data);

        $this->assertFalse($validationResult->isValid());
        $this->assertCount(3, $violations = $validationResult->getViolations());

        $this->assertSame('Unexpected NULL value.', $violations[0]->getMessageTemplate());
        $this->assertSame('objekt.flag', (string) $violations[0]->getBreadCrumbPath());

        $this->assertSame("Type '%s' expected.", $violations[1]->getMessageTemplate());
        $this->assertSame(['number'], $violations[1]->getParameters());
        $this->assertSame('objekt.pole[1]', (string) $violations[1]->getBreadCrumbPath());

        $this->assertSame("Type '%s' expected.", $violations[2]->getMessageTemplate());
        $this->assertSame(['string'], $violations[2]->getParameters());
        $this->assertSame('objekt.hash.5', (string) $violations[2]->getBreadCrumbPath());
    }

    private function createSchemaParser(): SchemaParserInterface
    {
        $variableTypeUnifyService = new VariableTypeUnifyService();
        $propertyReader = new PropertyTypeReader($variableTypeUnifyService);
        return new SchemaParser(new SchemaBuilderFactory($propertyReader, new SchemaBuilderDecorator([], [])));
    }

    private function createClassSchemaCollection(): ClassSchemaCollection
    {
        return new ClassSchemaCollection('#/components/schemas');
    }
}
