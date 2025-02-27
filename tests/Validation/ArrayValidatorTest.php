<?php

namespace ScrumWorks\OpenApiSchema\Tests\Validation;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Tests\DiTrait;
use ScrumWorks\OpenApiSchema\Validation\Result\BreadCrumbPathFactory;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactory;
use ScrumWorks\OpenApiSchema\Validation\Validator\ArrayValidator;
use ScrumWorks\OpenApiSchema\Validation\ValueSchemaValidatorInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\ArraySchemaData;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\StringSchemaData;

final class ArrayValidatorTest extends TestCase
{
    use DiTrait;

    public function testArrayValidation(): void
    {
        $arraySchema = new ArraySchemaData(itemsSchema: new StringSchemaData());

        $validator = new ArrayValidator(
            $this->getServiceFromContainerByType(BreadCrumbPathFactory::class),
            $this->getServiceFromContainerByType(ValidationResultBuilderFactory::class),
            $arraySchema,
            $this->getServiceFromContainerByType(ValueSchemaValidatorInterface::class)
        );

        self::assertCount(0, $validator->validate(['asd', 'qwe'])->getViolations());
        self::assertCount(0, $validator->validate([
            0 => 'asd',
            1 => 'qwe',
        ])->getViolations());

        $violations = $validator->validate([
            'asd' => 'qwe',
        ])->getViolations();
        self::assertEquals(1002, reset($violations)->getViolationCode());

        self::assertCount(1, $validator->validate([
            'asd' => 'qwe',
        ])->getViolations());
        $violations = $validator->validate([
            1 => 'asd',
            3 => 'qwe',
        ])->getViolations();
        self::assertCount(1, $violations);
        self::assertEquals(1020, reset($violations)->getViolationCode());
    }
}
