<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use ScrumWorks\OpenApiSchema\ReferencedSchemaBag;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilder;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactory;
use ScrumWorks\OpenApiSchema\Validation\ValueSchemaValidatorInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\UnionSchema;

final class UnionValidator extends AbstractValidator
{
    private UnionSchema $schema;

    private ValueSchemaValidatorInterface $valueValidator;

    public function __construct(
        BreadCrumbPathFactoryInterface $breadCrumbPathFactory,
        ValidationResultBuilderFactory $validationResultBuilderFactory,
        UnionSchema $schema,
        ValueSchemaValidatorInterface $valueValidator
    ) {
        parent::__construct($breadCrumbPathFactory, $validationResultBuilderFactory, $schema);

        $this->schema = $schema;
        $this->valueValidator = $valueValidator;
    }

    protected function doValidation(
        ValidationResultBuilder $resultBuilder,
        $data,
        ReferencedSchemaBag $referencedSchemaBag,
        BreadCrumbPathInterface $breadCrumbPath
    ): void {
        if (! $this->validateNullable($resultBuilder, $data, $breadCrumbPath)) {
            return;
        }

        if ($discriminatorName = $this->schema->getDiscriminatorPropertyName()) {
            if (! \is_object($data)) {
                $resultBuilder->addTypeViolation('object', $breadCrumbPath);
            } elseif (! \property_exists($data, $discriminatorName)) {
                $resultBuilder->addRequiredViolation($breadCrumbPath->withNextBreadCrumb($discriminatorName));
            } elseif (! ($discriminatorSchema = $this->schema->getPossibleSchemas()[$data->{$discriminatorName}] ?? null)) {
                $resultBuilder->addEnumViolation(
                    \array_keys($this->schema->getPossibleSchemas()),
                    $breadCrumbPath->withNextBreadCrumb($discriminatorName)
                );
            } else {
                $validationResult = $this->valueValidator->validate(
                    $discriminatorSchema,
                    $referencedSchemaBag,
                    $data,
                    $breadCrumbPath
                );
                $resultBuilder->mergeResult($validationResult);
            }
        } else {
            // `oneOf` semantics applied
            $matchCount = 0;
            foreach ($this->schema->getPossibleSchemas() as $schema) {
                $validationResult = $this->valueValidator->validate(
                    $schema,
                    $referencedSchemaBag,
                    $data,
                    $breadCrumbPath
                );
                if ($validationResult->isValid()) {
                    ++$matchCount;
                }
                // performance optimization
                if ($matchCount > 1) {
                    break;
                }
            }

            if ($matchCount === 0) {
                $resultBuilder->addOneOfNoMatchViolation($breadCrumbPath);
            } elseif ($matchCount > 1) {
                $resultBuilder->addOneOfAmbiguousViolation($breadCrumbPath);
            }
        }
    }

    protected function collectPossibleViolationExamples(
        ValidationResultBuilder $resultBuilder,
        ReferencedSchemaBag $referencedSchemaBag,
        BreadCrumbPathInterface $breadCrumbPath
    ): void {
        parent::collectPossibleViolationExamples($resultBuilder, $referencedSchemaBag, $breadCrumbPath);

        if ($discriminatorName = $this->schema->getDiscriminatorPropertyName()) {
            $resultBuilder->addTypeViolation('object', $breadCrumbPath);
            $resultBuilder->addRequiredViolation($breadCrumbPath->withNextBreadCrumb($discriminatorName));
            $resultBuilder->addEnumViolation(
                \array_keys($this->schema->getPossibleSchemas()),
                $breadCrumbPath->withNextBreadCrumb($discriminatorName)
            );
            foreach ($this->schema->getPossibleSchemas() as $schema) {
                $resultBuilder->mergeViolations(
                    $this->valueValidator->getPossibleViolationExamples($schema, $referencedSchemaBag, $breadCrumbPath)
                );
            }
        } else {
            $resultBuilder->addOneOfNoMatchViolation($breadCrumbPath);
            $resultBuilder->addOneOfAmbiguousViolation($breadCrumbPath);
        }
    }
}
