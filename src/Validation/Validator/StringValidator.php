<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use Nette\Utils\Strings;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilder;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactory;
use ScrumWorks\OpenApiSchema\Validation\Validator\Format\FormatValidatorInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

final class StringValidator extends AbstractValidator
{
    private StringSchema $schema;

    /**
     * @var array<string,FormatValidatorInterface> format => validator
     */
    private array $formatValidators;

    public function __construct(
        BreadCrumbPathFactoryInterface $breadCrumbPathFactory,
        ValidationResultBuilderFactory $validationResultBuilderFactory,
        StringSchema $schema,
        array $formatValidators
    ) {
        parent::__construct($breadCrumbPathFactory, $validationResultBuilderFactory, $schema);

        $this->schema = $schema;
        $this->formatValidators = $formatValidators;
    }

    protected function doValidation(
        ValidationResultBuilder $resultBuilder,
        $data,
        BreadCrumbPathInterface $breadCrumbPath
    ): void {
        if (! $this->validateNullable($resultBuilder, $data, $breadCrumbPath)) {
            return;
        }

        if (! \is_string($data)) {
            $resultBuilder->addTypeViolation('string', $breadCrumbPath);
            return;
        }

        $strlen = $this->schema->getFormat() === 'binary' ? \strlen($data) : mb_strlen($data);

        $minLength = $this->schema->getMinLength();
        if ($minLength !== null && $minLength > $strlen) {
            $resultBuilder->addMinLengthViolation($minLength, $breadCrumbPath);
        }

        $maxLength = $this->schema->getMaxLength();
        if ($maxLength !== null && $maxLength < $strlen) {
            $resultBuilder->addMaxLengthViolation($maxLength, $breadCrumbPath);
        }

        $pattern = $this->schema->getPattern();
        if ($pattern !== null) {
            $escapedPattern = str_replace('~', '\\~', $pattern);
            if (! Strings::match($data, "~${escapedPattern}~")) {
                $resultBuilder->addPatternViolation($pattern, $breadCrumbPath);
            }
        }

        $format = $this->schema->getFormat();
        if ($format !== null && ! $this->hasValidFormat($format, $data)) {
            $resultBuilder->addFormatViolation($format, $breadCrumbPath);
        }
    }

    protected function collectPossibleViolationExamples(
        ValidationResultBuilder $resultBuilder,
        BreadCrumbPathInterface $breadCrumbPath
    ): void {
        parent::collectPossibleViolationExamples($resultBuilder, $breadCrumbPath);

        $resultBuilder->addTypeViolation('string', $breadCrumbPath);

        $minLength = $this->schema->getMinLength();
        if ($minLength !== null) {
            $resultBuilder->addMinLengthViolation($minLength, $breadCrumbPath);
        }

        $maxLength = $this->schema->getMaxLength();
        if ($maxLength !== null) {
            $resultBuilder->addMaxLengthViolation($maxLength, $breadCrumbPath);
        }

        $pattern = $this->schema->getPattern();
        if ($pattern !== null) {
            $resultBuilder->addPatternViolation($pattern, $breadCrumbPath);
        }

        $format = $this->schema->getFormat();
        if ($format !== null) {
            $resultBuilder->addFormatViolation($format, $breadCrumbPath);
        }
    }

    private function hasValidFormat(string $format, string $data): bool
    {
        $validator = $this->formatValidators[$format] ?? null;
        if (! $validator) {
            // openapi says that unknown formats should be ignored
            return true;
        }

        return $validator->hasValidFormat($data);
    }
}
