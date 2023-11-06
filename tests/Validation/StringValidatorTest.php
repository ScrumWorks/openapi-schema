<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\Validation;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Tests\DiTrait;
use ScrumWorks\OpenApiSchema\Validation\Result\BreadCrumbPathFactory;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactory;
use ScrumWorks\OpenApiSchema\Validation\Validator\StringValidator;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

class StringValidatorTest extends TestCase
{
    use DiTrait;

    public function test(): void
    {
        $stringSchema = new StringSchema(pattern: '^[a-zA-Z0-9-_]+$');

        $validator = new StringValidator(
            $this->getServiceFromContainerByType(BreadCrumbPathFactory::class),
            $this->getServiceFromContainerByType(ValidationResultBuilderFactory::class),
            $stringSchema,
            []
        );

        self::assertCount(0, $validator->validate('asd')->getViolations());
        self::assertCount(1, $validator->validate('asd' . PHP_EOL)->getViolations());
    }
}
