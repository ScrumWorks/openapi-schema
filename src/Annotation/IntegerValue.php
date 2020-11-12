<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 *
 * Doctrine annotations reader uses `@var` annotations and doesn't know nullability
 */
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
