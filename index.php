<?php

require_once __DIR__ . '/vendor/autoload.php';

class User
{
    public string $name;
    public string $surname;
}

class Test
{
    /**
     * @var integer
     */
    public int $test;

    public ?string $name;

    /**
     * @var User[]
     */
    public array $users;
}

$schemaParser = new \ScrumWorks\OpenApiSchema\SchemaParser(
    new \ScrumWorks\PropertyReader\PropertyTypeReader(
        new \ScrumWorks\PropertyReader\VariableTypeUnifyService()
    )
);
$schema = $schemaParser->getEntitySchema('Test');
// Now you can get informations about entity schema
assert($schema->getPropertiesSchemas()['test'] instanceof \ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema);

// Getting OpenAPI entity schema (result is PHP array)
$openApiTranslator = new \ScrumWorks\OpenApiSchema\OpenApiTranslator();
$openApiValueSchema = $openApiTranslator->translateValueSchema($schema);
