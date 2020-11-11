<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator;

use Nette\Utils\Strings;
use RuntimeException;
use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPath;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\Result\ValidationResultBuilderInterface;
use ScrumWorks\OpenApiSchema\ValueSchema\StringSchema;

final class StringValidator extends AbstractValidator
{
    private StringSchema $schema;

    public function __construct(
        ValidationResultBuilderFactoryInterface $validationResultBuilderFactory,
        StringSchema $schema
    ) {
        parent::__construct($validationResultBuilderFactory, $schema);

        $this->schema = $schema;
    }

    protected function doValidation(
        ValidationResultBuilderInterface $resultBuilder,
        $data,
        BreadCrumbPath $breadCrumbPath
    ): void {
        if (! $this->validateNullable($resultBuilder, $data, $breadCrumbPath)) {
            return;
        }

        if (! \is_string($data)) {
            $resultBuilder->addTypeViolation('string', $breadCrumbPath);
            return;
        }

        $strlen = $this->schema->getFormat() === 'binary' ? \strlen($data) : \mb_strlen($data);

        if (($minLength = $this->schema->getMinLength()) !== null && $minLength >= $strlen) {
            $resultBuilder->addMinLengthViolation($minLength, $breadCrumbPath);
        }
        if (($maxLength = $this->schema->getMaxLength()) !== null && $maxLength <= $strlen) {
            $resultBuilder->addMaxLengthViolation($maxLength, $breadCrumbPath);
        }
        if (($pattern = $this->schema->getPattern()) !== null) {
            $escapedPattern = \str_replace('~', '\\~', $pattern);
            if (! Strings::match($data, "~${escapedPattern}~")) {
                $resultBuilder->addPatternViolation($breadCrumbPath);
            }
        }
        if (
            ($format = $this->schema->getFormat()) !== null
            && ! Strings::match($data, $this->formatToPattern($format))
        ) {
            $resultBuilder->addFormatViolation($breadCrumbPath);
        }
    }

    private function formatToPattern(string $format): string
    {
        switch ($format) {
            case 'password':
            case 'binary': return '~.*~';
            case 'date': return '~[0-9]{4}-[0-9]{2}-[0-9]{2}~';
            case 'date-time': return '~[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}(\\.[0-9]+(Z|([+-][0-9]{2}:[0-9]{2})))?~';

            case 'byte':
                throw new RuntimeException("Not implemented format '${format}'.");

            default:
                throw new RuntimeException("Not supported format '${format}'.");
        }
    }
}