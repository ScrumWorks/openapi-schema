<?php

use Lang\OpenApiDefinition\OpenApiTranslator;
use Symfony\Component\Yaml\Yaml;

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
     * @description Some good data
     */
    public int $test;

    public ?string $name;

    /**
     * @var int[][]
     */
    public array $arr;

    public User $user;

    /**
     * @var User[]
     */
    public array $test2;
}

$schemaParser = new \Lang\OpenApiDefinition\SchemaParser(
    new \Amateri\PropertyReader\PropertyReader(
        new \Amateri\PropertyReader\VariableTypeUnifyService()
    )
);
$schema = $schemaParser->getEntitySchema('Test');

$openApiTranslator = new OpenApiTranslator();
$openApiValueSchema = $openApiTranslator->translateValueSchema($schema);

$schema = Yaml::dump($openApiValueSchema);
print "$schema\n";