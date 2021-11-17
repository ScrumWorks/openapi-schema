<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

use Attribute;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 *
 * Doctrine annotations reader uses `@var` annotations and doesn't know nullability
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
final class StringValue implements ValueInterface
{
    /**
     * @var int
     */
    public ?int $minLength = null;

    /**
     * @var int
     */
    public ?int $maxLength = null;

    /**
     * @var string
     */
    public ?string $format = null;

    /**
     * @var string
     */
    public ?string $pattern = null;
}
