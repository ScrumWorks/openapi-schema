<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation;

use ScrumWorks\OpenApiSchema\Exception\LogicException;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResult;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidityViolation;
use ScrumWorks\OpenApiSchema\Validation\Validator\AbstractValidator;
use ScrumWorks\OpenApiSchema\Validation\Validator\ArrayValidator;
use ScrumWorks\OpenApiSchema\Validation\Validator\BooleanValidator;
use ScrumWorks\OpenApiSchema\Validation\Validator\EnumValidator;
use ScrumWorks\OpenApiSchema\Validation\Validator\FloatValidator;
use ScrumWorks\OpenApiSchema\Validation\Validator\HashmapValidator;
use ScrumWorks\OpenApiSchema\Validation\Validator\IntegerValidator;
use ScrumWorks\OpenApiSchema\Validation\Validator\MixedValidator;
use ScrumWorks\OpenApiSchema\Validation\Validator\ObjectValidator;
use ScrumWorks\OpenApiSchema\Validation\Validator\StringValidator;
use ScrumWorks\OpenApiSchema\ValueSchema\ArraySchema;
use ScrumWorks\OpenApiSchema\ValueSchema\BooleanSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\EnumSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\FloatSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\HashmapSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\MixedSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

final class ValueValidator
{
    private ValidationResultBuilderFactoryInterface $validationResultBuilderFactory;

    public function __construct(ValidationResultBuilderFactoryInterface $validationResultBuilderFactory)
    {
        $this->validationResultBuilderFactory = $validationResultBuilderFactory;
    }

    /**
     * @param mixed $data
     */
    public function validate(
        ValueSchemaInterface $schema,
        $data,
        ?BreadCrumbPath $breadCrumbPath = null
    ): ValidationResult {
        return $this->createValidator($schema)->validate($data, $breadCrumbPath);
    }

    /**
     * @return ValidityViolation[]
     */
    public function getPossibleViolationExamples(
        ValueSchemaInterface $schema,
        ?BreadCrumbPath $breadCrumbPath = null
    ): array {
        return $this->createValidator($schema)->getPossibleViolationExamples($breadCrumbPath);
    }

    private function createValidator(ValueSchemaInterface $schema): AbstractValidator
    {
        if ($schema instanceof ArraySchema) {
            return new ArrayValidator($this->validationResultBuilderFactory, $schema, $this);
        } elseif ($schema instanceof BooleanSchema) {
            return new BooleanValidator($this->validationResultBuilderFactory, $schema);
        } elseif ($schema instanceof EnumSchema) {
            return new EnumValidator($this->validationResultBuilderFactory, $schema);
        } elseif ($schema instanceof FloatSchema) {
            return new FloatValidator($this->validationResultBuilderFactory, $schema);
        } elseif ($schema instanceof HashmapSchema) {
            return new HashmapValidator($this->validationResultBuilderFactory, $schema, $this);
        } elseif ($schema instanceof IntegerSchema) {
            return new IntegerValidator($this->validationResultBuilderFactory, $schema);
        } elseif ($schema instanceof ObjectSchema) {
            return new ObjectValidator($this->validationResultBuilderFactory, $schema, $this);
        } elseif ($schema instanceof StringSchema) {
            return new StringValidator($this->validationResultBuilderFactory, $schema);
        } elseif ($schema instanceof MixedSchema) {
            return new MixedValidator($this->validationResultBuilderFactory, $schema);
        }

        throw new LogicException('Unexpected value schema type: ' . \get_class($schema));
    }
}
