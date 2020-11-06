<?php

use Lang\OpenApiDefinition\OpenApiTranslator;
use Lang\OpenApiDefinition\ValueSchema\Builder\ArraySchemaBuilder;
use Lang\OpenApiDefinition\ValueSchema\Builder\EnumSchemaBuilder;
use Lang\OpenApiDefinition\ValueSchema\Builder\ObjectSchemaBuilder;
use Lang\OpenApiDefinition\ValueSchema\Builder\StringSchemaBuilder;
use Symfony\Component\Yaml\Yaml;

require_once __DIR__ . '/vendor/autoload.php';

/*$userBuilder = new ObjectSchemaBuilder();
$userSchema = $userBuilder
    ->withPropertiesSchemas([
        'name' => (new StringSchemaBuilder())->build(),
        'surname' => (new StringSchemaBuilder())->build(),
        'sex' => (new EnumSchemaBuilder())->withEnum(['F', 'M'])->build(),
        'note' => (new StringSchemaBuilder())->withNullable(true)->build(),
    ])
    ->withRequiredProperties(['name', 'surname', 'sex'])
    ->build();

$arrayBuilder = new ArraySchemaBuilder();
$arraySchema = $arrayBuilder
    ->withItemsSchema($userSchema)
    ->withMinItems(1)
    ->build();

$openApiTranslator = new OpenApiTranslator();
$openApiValueSchema = $openApiTranslator->translateValueSchema($arraySchema);

$schema = Yaml::dump($openApiValueSchema);
print "$schema\n";*/

class Test
{
    /**
     * @var integer
     * @description Some good data
     */
    public int $test;

    public ?string $name;
}
$test = new Test();
$reflection = new ReflectionObject($test);

$objectMapping = new \Lang\OpenApiDefinition\ObjectMapping();
$schema = $objectMapping->getSchema($reflection);

$openApiTranslator = new OpenApiTranslator();
$openApiValueSchema = $openApiTranslator->translateValueSchema($schema);

$schema = Yaml::dump($openApiValueSchema);
print "$schema\n";