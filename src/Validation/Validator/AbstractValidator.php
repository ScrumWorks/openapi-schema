<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\ReferencedSchemaBag;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilder;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactory;
use ScrumWorks\OpenApiSchema\Validation\ValidationResultInterface;
use ScrumWorks\OpenApiSchema\Validation\ValidityViolationInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\AbstractValueSchema;

abstract class AbstractValidator
{
    private BreadCrumbPathFactoryInterface $breadCrumbPathFactory;

    private ValidationResultBuilderFactory $validationResultBuilderFactory;

    private AbstractValueSchema $schema;

    public function __construct(
        BreadCrumbPathFactoryInterface $breadCrumbPathFactory,
        ValidationResultBuilderFactory $validationResultBuilderFactory,
        AbstractValueSchema $schema
    ) {
        $this->breadCrumbPathFactory = $breadCrumbPathFactory;
        $this->validationResultBuilderFactory = $validationResultBuilderFactory;
        $this->schema = $schema;
    }

    /**
     * @param mixed $data
     */
    public function validate(
        $data,
        ReferencedSchemaBag $referencedSchemaBag,
        ?BreadCrumbPathInterface $breadCrumbPath = null
    ): ValidationResultInterface {
        $breadCrumbPath ??= $this->breadCrumbPathFactory->create();
        $resultBuilder = $this->validationResultBuilderFactory->create();

        $this->doValidation($resultBuilder, $data, $referencedSchemaBag, $breadCrumbPath);

        return $resultBuilder->createResult();
    }

    /**
     * @return ValidityViolationInterface[]
     */
    public function getPossibleViolationExamples(
        ReferencedSchemaBag $referencedSchemaBag,
        ?BreadCrumbPathInterface $breadCrumbPath = null
    ): array {
        $breadCrumbPath ??= $this->breadCrumbPathFactory->create();
        $resultBuilder = $this->validationResultBuilderFactory->create();

        $this->collectPossibleViolationExamples($resultBuilder, $referencedSchemaBag, $breadCrumbPath);

        return $resultBuilder->createResult()->getViolations();
    }

    /**
     * Returns FALSE if validation should not continue
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
        ReferencedSchemaBag $referencedSchemaBag,
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
        ReferencedSchemaBag $referencedSchemaBag,
        BreadCrumbPathInterface $breadCrumbPath
    ): void;
}
