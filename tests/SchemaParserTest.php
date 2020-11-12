<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests;

use LogicException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionProperty;
use ScrumWorks\OpenApiSchema\PropertySchemaDecorator\SimplePropertySchemaDecorator;
use ScrumWorks\OpenApiSchema\SchemaParser;
use ScrumWorks\OpenApiSchema\ValueSchema\ArraySchema;
use ScrumWorks\OpenApiSchema\ValueSchema\BooleanSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\AbstractSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\Builder\EnumSchemaBuilder;
use ScrumWorks\OpenApiSchema\ValueSchema\EnumSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\FloatSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\HashmapSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\MixedSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;
use ScrumWorks\PropertyReader\PropertyTypeReader;
use ScrumWorks\PropertyReader\VariableType\ArrayVariableType;
use ScrumWorks\PropertyReader\VariableType\ClassVariableType;
use ScrumWorks\PropertyReader\VariableType\MixedVariableType;
use ScrumWorks\PropertyReader\VariableType\ScalarVariableType;
use ScrumWorks\PropertyReader\VariableType\VariableTypeInterface;
use ScrumWorks\PropertyReader\VariableTypeUnifyService;

class EnumTestClass
{
    public string $enum;
}

class UnknownVariableType implements VariableTypeInterface
{
    public function isNullable(): bool
    {
        return true;
    }

    public function getTypeName(): string
    {
        return 'TESTING-CLASS';
    }

    public function equals(VariableTypeInterface $object): bool
    {
        return false;
    }
}

class SchemaParserTest extends TestCase
{
    protected SchemaParser $schemaParser;

    protected function setUp(): void
    {
        $this->schemaParser = new SchemaParser(new PropertyTypeReader(new VariableTypeUnifyService(), ));
    }

    public function testGetEntitySchemaOnNonExistingClass(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("Class 'abc-not-existing' doesn't exists");
        $this->schemaParser->getEntitySchema('abc-not-existing');
    }

    public function testNullVariableType(): void
    {
        $schema = $this->schemaParser->getVariableTypeSchema(null);
        $this->assertInstanceOf(MixedSchema::class, $schema);
        $this->assertTrue($schema->isNullable());
    }

    public function testMixedVariableType(): void
    {
        $variableType = new MixedVariableType();
        $schema = $this->schemaParser->getVariableTypeSchema($variableType);
        $this->assertInstanceOf(MixedSchema::class, $schema);
        $this->assertTrue($schema->isNullable());
    }

    public function testIntegerVariableType(): void
    {
        $variableType = new ScalarVariableType(ScalarVariableType::TYPE_INTEGER, false);
        $this->assertInstanceOf(IntegerSchema::class, $this->schemaParser->getVariableTypeSchema($variableType));
    }

    public function testFloatVariableType(): void
    {
        $variableType = new ScalarVariableType(ScalarVariableType::TYPE_FLOAT, false);
        $this->assertInstanceOf(FloatSchema::class, $this->schemaParser->getVariableTypeSchema($variableType));
    }

    public function testBooleanVariableType(): void
    {
        $variableType = new ScalarVariableType(ScalarVariableType::TYPE_BOOLEAN, false);
        $this->assertInstanceOf(BooleanSchema::class, $this->schemaParser->getVariableTypeSchema($variableType));
    }

    public function testStringVariableType(): void
    {
        $variableType = new ScalarVariableType(ScalarVariableType::TYPE_STRING, false);
        $this->assertInstanceOf(StringSchema::class, $this->schemaParser->getVariableTypeSchema($variableType));
    }

    public function testArrayVariableType(): void
    {
        $variableType = new ArrayVariableType(
            null,
            new ScalarVariableType(ScalarVariableType::TYPE_STRING, false),
            false
        );
        $this->assertInstanceOf(ArraySchema::class, $this->schemaParser->getVariableTypeSchema($variableType));

        $variableType = new ArrayVariableType(
            new ScalarVariableType(ScalarVariableType::TYPE_STRING, false),
            new ScalarVariableType(ScalarVariableType::TYPE_STRING, false),
            false
        );
        $this->assertInstanceOf(HashmapSchema::class, $this->schemaParser->getVariableTypeSchema($variableType));
    }

    public function testClassVariableType(): void
    {
        $variableType = new ClassVariableType(self::class, false);
        $this->assertInstanceOf(ObjectSchema::class, $this->schemaParser->getVariableTypeSchema($variableType));
    }

    public function testEnum(): void
    {
        $schemaParser = new SchemaParser(
            new PropertyTypeReader(new VariableTypeUnifyService(), ),
            new class() extends SimplePropertySchemaDecorator {
                public function isEnum(ReflectionProperty $propertyReflection): bool
                {
                    return true;
                }

                public function decorateEnumSchemaBuilder(
                    EnumSchemaBuilder $builder,
                    ReflectionProperty $propertyReflection
                ): EnumSchemaBuilder {
                    return $builder->withEnum(['a', 'b']);
                }
            }
        );

        $classReflexion = new ReflectionClass(EnumTestClass::class);

        $this->assertInstanceOf(
            EnumSchema::class,
            $schemaParser->getPropertySchema($classReflexion->getProperty('enum'))
        );
    }

    public function testUnknownVariableType(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(
            "Unprocessable VariableTypeInterface 'TESTING-CLASS' (class ScrumWorks\OpenApiSchema\Tests\UnknownVariableType)"
        );
        $variableType = new UnknownVariableType();
        $this->schemaParser->getVariableTypeSchema($variableType);
    }

    public function testDecorateValueSchema(): void
    {
        $schemaParser = new SchemaParser(
            new PropertyTypeReader(new VariableTypeUnifyService(), ),
            new class() extends SimplePropertySchemaDecorator {
                public function decorateValueSchemaBuilder(
                    AbstractSchemaBuilder $builder,
                    ReflectionProperty $propertyReflection
                ): AbstractSchemaBuilder {
                    return $builder->withDescription('SOME DESCRIPTION');
                }
            }
        );

        $classReflexion = new ReflectionClass(EnumTestClass::class);

        $this->assertSame(
            'SOME DESCRIPTION',
            $schemaParser->getPropertySchema($classReflexion->getProperty('enum'))->getDescription()
        );
    }
}
