<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use LogicException;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactory;
use ScrumWorks\OpenApiSchema\Validation\Validator\Format\DateTimeValidator;
use ScrumWorks\OpenApiSchema\Validation\Validator\Format\DateValidator;
use ScrumWorks\OpenApiSchema\Validation\Validator\Format\FormatValidatorInterface;
use ScrumWorks\OpenApiSchema\Validation\ValueSchemaValidatorInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\ArraySchema;
use ScrumWorks\OpenApiSchema\ValueSchema\BooleanSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\EnumSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\FloatSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\HashmapSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\IntegerSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\MixedSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ObjectSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\UnionSchema;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

class ValidatorFactory
{
    protected BreadCrumbPathFactoryInterface $breadCrumbPathFactory;

    protected ValidationResultBuilderFactory $validationResultBuilderFactory;

    public function __construct(
        BreadCrumbPathFactoryInterface $breadCrumbPathFactory,
        ValidationResultBuilderFactory $validationResultBuilderFactory
    ) {
        $this->breadCrumbPathFactory = $breadCrumbPathFactory;
        $this->validationResultBuilderFactory = $validationResultBuilderFactory;
    }

    public function createValidator(
        ValueSchemaInterface $schema,
        ValueSchemaValidatorInterface $valueSchemaValidator
    ): AbstractValidator {
        if ($schema instanceof ArraySchema) {
            return new ArrayValidator(
                $this->breadCrumbPathFactory,
                $this->validationResultBuilderFactory,
                $schema,
                $valueSchemaValidator,
            );
        } elseif ($schema instanceof BooleanSchema) {
            return new BooleanValidator(
                $this->breadCrumbPathFactory,
                $this->validationResultBuilderFactory,
                $schema,
            );
        } elseif ($schema instanceof EnumSchema) {
            return new EnumValidator($this->breadCrumbPathFactory, $this->validationResultBuilderFactory, $schema);
        } elseif ($schema instanceof FloatSchema) {
            return new FloatValidator(
                $this->breadCrumbPathFactory,
                $this->validationResultBuilderFactory,
                $schema,
            );
        } elseif ($schema instanceof HashmapSchema) {
            return new HashmapValidator(
                $this->breadCrumbPathFactory,
                $this->validationResultBuilderFactory,
                $schema,
                $valueSchemaValidator,
            );
        } elseif ($schema instanceof IntegerSchema) {
            return new IntegerValidator(
                $this->breadCrumbPathFactory,
                $this->validationResultBuilderFactory,
                $schema,
            );
        } elseif ($schema instanceof ObjectSchema) {
            return new ObjectValidator(
                $this->breadCrumbPathFactory,
                $this->validationResultBuilderFactory,
                $schema,
                $valueSchemaValidator,
            );
        } elseif ($schema instanceof StringSchema) {
            return new StringValidator(
                $this->breadCrumbPathFactory,
                $this->validationResultBuilderFactory,
                $schema,
                $this->createFormatValidators(),
            );
        } elseif ($schema instanceof MixedSchema) {
            return new MixedValidator(
                $this->breadCrumbPathFactory,
                $this->validationResultBuilderFactory,
                $schema,
            );
        } elseif ($schema instanceof UnionSchema) {
            return new UnionValidator(
                $this->breadCrumbPathFactory,
                $this->validationResultBuilderFactory,
                $schema,
                $valueSchemaValidator
            );
        }

        throw new LogicException('Unexpected value schema type: ' . \get_class($schema));
    }

    /**
     * @return array<string,FormatValidatorInterface>
     */
    protected function createFormatValidators(): array
    {
        return [
            'date' => new DateValidator(),
            'date-time' => new DateTimeValidator(),
        ];
    }
}
