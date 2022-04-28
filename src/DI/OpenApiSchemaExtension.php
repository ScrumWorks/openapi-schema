<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Definition;
use Nette\DI\Definitions\Reference;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\PhpGenerator\ClassType;
use ScrumWorks\OpenApiSchema\OpenApiTranslator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassDecorator\AttributeClassSchemaDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\ClassDecorator\DateTimeClassSchemaDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\Decorator\PropertyDecorator\AttributePropertySchemaDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\SchemaBuilderDecorator;
use ScrumWorks\OpenApiSchema\SchemaBuilder\SchemaBuilderFactory;
use ScrumWorks\OpenApiSchema\SchemaParser;
use ScrumWorks\OpenApiSchema\Validation\Result\BreadCrumbPathFactory;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactory;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidityViolationFactory;
use ScrumWorks\OpenApiSchema\Validation\Validator\ValidatorFactory;
use ScrumWorks\OpenApiSchema\Validation\Validator\ValueSchemaValidator;
use ScrumWorks\PropertyReader\PropertyTypeReader;
use ScrumWorks\PropertyReader\VariableTypeUnifyService;

class OpenApiSchemaExtension extends CompilerExtension
{
    private const TAG_PRIORITY = 'priority';

    private const SERVICE_NAME_SCHEMA_BUILDER_DECORATOR = 'schemaBuilderDecorator';

    public function beforeCompile(): void
    {
        parent::beforeCompile();

        $this->addService('dateTimeClassSchemaDecorator', DateTimeClassSchemaDecorator::class)
            ->addTag(self::TAG_PRIORITY, 90);
        $this->addService('attributeClassSchemaDecorator', AttributeClassSchemaDecorator::class)
            ->addTag(self::TAG_PRIORITY, 100);
        $this->addService('attributePropertySchemaDecorator', AttributePropertySchemaDecorator::class)
            ->addTag(self::TAG_PRIORITY, 100);
        $this->addService(self::SERVICE_NAME_SCHEMA_BUILDER_DECORATOR, SchemaBuilderDecorator::class);

        $this->addService('variableTypeUnifyService', VariableTypeUnifyService::class);
        $this->addService('propertyTypeReader', PropertyTypeReader::class);
        $this->addService('schemaBuilderFactory', SchemaBuilderFactory::class);
        $this->addService('schemaParser', SchemaParser::class);
        $this->addService('openApiTranslator', OpenApiTranslator::class);
        $this->addService('breadCrumbPathFactory', BreadCrumbPathFactory::class);
        $this->addService('validityViolationFactory', ValidityViolationFactory::class);
        $this->addService('validationResultBuilderFactory', ValidationResultBuilderFactory::class);
        $this->addService('validatorFactory', ValidatorFactory::class);
        $this->addService('valueSchemaValidator', ValueSchemaValidator::class);
    }

    public function afterCompile(ClassType $class): void
    {
        // schema decorators order by priority tag
        $def = $this->getContainerBuilder()->getDefinition($this->prefix(self::SERVICE_NAME_SCHEMA_BUILDER_DECORATOR));
        \assert($def instanceof ServiceDefinition);
        $factory = $def->getFactory();

        for ($i = 0; $i < \count($factory->arguments); ++$i) {
            \usort($factory->arguments[$i], function (Reference $a, Reference $b) {
                return $this->getServicePriority($a->getValue()) <=> $this->getServicePriority($b->getValue());
            });
        }
    }

    private function addService(string $name, string $class): Definition
    {
        return $this->getContainerBuilder()->addDefinition($this->prefix($name))->setType($class);
    }

    private function getServicePriority(string $referenceValue): int
    {
        return $this->getContainerBuilder()->getDefinition($referenceValue)->getTag(self::TAG_PRIORITY) ?? PHP_INT_MAX;
    }
}
