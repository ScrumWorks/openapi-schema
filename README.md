# OpenApi schema parser

## Installation
```
composer require scrumworks/openapi-schema
```

## Example

```php
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
    new \Amateri\PropertyReader\PropertyReader(
        new \Amateri\PropertyReader\VariableTypeUnifyService()
    )
);
$schema = $schemaParser->getEntitySchema('Test');
// Now you can get informations about entity schema
assert($schema->propertiesSchemas[0] instanceof \ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema);

// Getting OpenAPI entity schema (result is PHP array)
$openApiTranslator = new \ScrumWorks\OpenApiSchema\OpenApiTranslator();
$openApiValueSchema = $openApiTranslator->translateValueSchema($schema);
```

## Testing
You can run the tests with:

```
composer run-script test
```

## Contribution Guide
Feel free to open an Issue or add a Pull request.

## Credits
People:
- [Tomas Lang](https://github.com/detrandix)
- [Adam Lutka](https://github.com/AdamLutka)