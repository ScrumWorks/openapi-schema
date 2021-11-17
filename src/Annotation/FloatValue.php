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
final class FloatValue implements ValueInterface
{
    /**
     * @var float
     */
    public ?float $minimum = null;

    /**
     * @var float
     */
    public ?float $maximum = null;

    /**
     * @var bool
     */
    public ?bool $exclusiveMinimum = null;

    /**
     * @var bool
     */
    public ?bool $exclusiveMaximum = null;

    /**
     * @var float
     */
    public ?float $multipleOf = null;
}
