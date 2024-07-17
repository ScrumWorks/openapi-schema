<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilder;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactory;
use ScrumWorks\OpenApiSchema\Validation\ValidationResultInterface;
use ScrumWorks\OpenApiSchema\Validation\ValidityViolationInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\ValueSchemaInterface;

abstract class AbstractValidator
{
    private BreadCrumbPathFactoryInterface $breadCrumbPathFactory;

    private ValidationResultBuilderFactory $validationResultBuilderFactory;

    private ValueSchemaInterface $schema;

    public function __construct(
        BreadCrumbPathFactoryInterface $breadCrumbPathFactory,
        ValidationResultBuilderFactory $validationResultBuilderFactory,
        ValueSchemaInterface $schema,
    ) {
        $this->breadCrumbPathFactory = $breadCrumbPathFactory;
        $this->validationResultBuilderFactory = $validationResultBuilderFactory;
        $this->schema = $schema;
    }

    /**
     * @param mixed $data
     */
    public function validate($data, ?BreadCrumbPathInterface $breadCrumbPath = null): ValidationResultInterface
    {
        $breadCrumbPath ??= $this->breadCrumbPathFactory->create();
        $resultBuilder = $this->validationResultBuilderFactory->create();

        $this->doValidation($resultBuilder, $data, $breadCrumbPath);

        return $resultBuilder->createResult();
    }

    /**
     * @return ValidityViolationInterface[]
     */
    public function getPossibleViolationExamples(?BreadCrumbPathInterface $breadCrumbPath = null): array
    {
        $breadCrumbPath ??= $this->breadCrumbPathFactory->create();
        $resultBuilder = $this->validationResultBuilderFactory->create();

        $this->collectPossibleViolationExamples($resultBuilder, $breadCrumbPath);

        return $resultBuilder->createResult()->getViolations();
    }

    /**
     * Returns FALSE if validation should not continue
     *
     * @param mixed $data
     */
    protected function validateNullable(
        ValidationResultBuilder $resultBuilder,
        $data,
        BreadCrumbPathInterface $breadCrumbPath
    ): bool {
        if ($data === null) {
            if (! $this->schema->isNullable()) {
                $resultBuilder->addNullableViolation($breadCrumbPath);
            }

            return false;
        }

        return true;
    }

    protected function collectPossibleViolationExamples(
        ValidationResultBuilder $resultBuilder,
        BreadCrumbPathInterface $breadCrumbPath
    ): void {
        if (! $this->schema->isNullable()) {
            $resultBuilder->addNullableViolation($breadCrumbPath);
        }
    }

    /**
     * @param mixed $data
     */
    abstract protected function doValidation(
        ValidationResultBuilder $resultBuilder,
        $data,
        BreadCrumbPathInterface $breadCrumbPath
    ): void;
}
