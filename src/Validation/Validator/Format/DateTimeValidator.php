<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Validator\Format;

use Nette\Utils\Strings;

final class DateTimeValidator implements FormatValidatorInterface
{
    public function hasValidFormat(string $string): bool
    {
        return (bool) Strings::match(
            $string,
            '~^[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}:[0-9]{2}(\\.[0-9]+)?(Z|([+-][0-9]{2}:[0-9]{2}))?$~i'
        );
    }
}
