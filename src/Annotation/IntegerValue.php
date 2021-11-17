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
final class IntegerValue implements ValueInterface
{
    /**
     * @var int
     */
    public ?int $minimum = null;

    /**
     * @var int
     */
    public ?int $maximum = null;

    /**
     * @var bool
     */
    public ?bool $exclusiveMinimum = null;

    /**
     * @var bool
     */
    public ?bool $exclusiveMaximum = null;

    /**
     * @var int
     */
    public ?int $multipleOf = null;
}
