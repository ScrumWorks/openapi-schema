<?php

use ScrumWorks\OpenApiSchema\OpenApiTranslator;
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

$schemaParser = new \ScrumWorks\OpenApiSchema\SchemaParser(
    new \Amateri\PropertyReader\PropertyReader(
        new \Amateri\PropertyReader\VariableTypeUnifyService()
    )
);
$schema = $schemaParser->getEntitySchema('Test');

$openApiTranslator = new OpenApiTranslator();
$openApiValueSchema = $openApiTranslator->translateValueSchema($schema);

$schema = Yaml::dump($openApiValueSchema);
print "$schema\n";