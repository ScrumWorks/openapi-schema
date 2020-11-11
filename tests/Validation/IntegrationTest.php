<?php

declare(strict_types = 1);

namespace ScrumWorks\OpenApiSchema\Tests\Validation;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\SchemaParser;
use ScrumWorks\OpenApiSchema\SchemaParserInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderInterface;
use ScrumWorks\OpenApiSchema\Validation\ValueValidator;
use ScrumWorks\PropertyReader\PropertyTypeReader;
use ScrumWorks\PropertyReader\VariableTypeUnifyService;

class IntegrationTest extends TestCase
{
    public function testValid(): void
    {
        $data = json_decode('
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
        $validator = $this->createValidator();

        $schema = $schemaParser->getEntitySchema(TestEntity::class);
        $validationResult = $validator->validate($schema, $data);

        $this->assertTrue($validationResult->isValid());
        $this->assertSame([], $validationResult->getViolations());
    }

    public function testInvalid(): void
    {
        $data = json_decode('
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
        $validator = $this->createValidator();

        $schema = $schemaParser->getEntitySchema(TestEntity::class);
        $validationResult = $validator->validate($schema, $data);

        $this->assertFalse($validationResult->isValid());
        $this->assertCount(3, $violations = $validationResult->getViolations());

        $this->assertSame("Unexpected NULL value.", $violations[0]->getMessage());
        $this->assertSame("objekt.flag", (string) $violations[0]->getBreadCrumbPath());

        $this->assertSame("Type 'number' expected.", $violations[1]->getMessage());
        $this->assertSame("objekt.pole[1]", (string) $violations[1]->getBreadCrumbPath());

        $this->assertSame("Type 'string' expected.", $violations[2]->getMessage());
        $this->assertSame("objekt.hash.5", (string) $violations[2]->getBreadCrumbPath());
    }

    private function createValidator(): ValueValidator
    {
        $resultBuilderFactory = new class implements ValidationResultBuilderFactoryInterface {
            public function create(): ValidationResultBuilderInterface
            {
                return new TestValidationResultBuilder();
            }
        };

        return new ValueValidator($resultBuilderFactory);
    }

    private function createSchemaParser(): SchemaParserInterface
    {
        $variableTypeUnifyService = new VariableTypeUnifyService();
        $propertyReader = new PropertyTypeReader($variableTypeUnifyService);
        return new SchemaParser($propertyReader, new AnnotationReader());
    }
}
