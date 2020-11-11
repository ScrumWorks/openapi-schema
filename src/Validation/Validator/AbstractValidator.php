<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPath;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResult;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\AbstractValueSchema;

abstract class AbstractValidator
{
    private ValidationResultBuilderFactoryInterface $validationResultBuilderFactory;

    private AbstractValueSchema $schema;

    public function __construct(
        ValidationResultBuilderFactoryInterface $validationResultBuilderFactory,
        AbstractValueSchema $schema
    ) {
        $this->validationResultBuilderFactory = $validationResultBuilderFactory;
        $this->schema = $schema;
    }

    /**
     * @param mixed $data
     */
    public function validate($data, ?BreadCrumbPath $breadCrumbPath = null): ValidationResult
    {
        $breadCrumbPath ??= new BreadCrumbPath();
        $resultBuilder = $this->validationResultBuilderFactory->create();

        $this->doValidation($resultBuilder, $data, $breadCrumbPath);

        return $resultBuilder->createResult();
    }

    /**
     * Returns FALSE if validation should not continue
     * @param mixed $data
     */
    protected function validateNullable(
        ValidationResultBuilderInterface $resultBuilder,
        $data,
        BreadCrumbPath $breadCrumbPath
    ): bool {
        if ($data === null) {
            if (! $this->schema->isNullable()) {
                $resultBuilder->addNullViolation($breadCrumbPath);
            }

            return false;
        }

        return true;
    }

    /**
     * @param mixed $data
     */
    abstract protected function doValidation(
        ValidationResultBuilderInterface $resultBuilder,
        $data,
        BreadCrumbPath $breadCrumbPath
    ): void;
}
