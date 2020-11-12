<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 *
 * Doctrine annotations reader uses `@var` annotations and doesn't know nullability
 */
final class ArrayValue implements ValueInterface
{
    /**
     * @var int
     */
    public ?int $minItems = null;

    /**
     * @var int
     */
    public ?int $maxItems = null;

    /**
     * @var bool
     */
    public ?bool $uniqueItems = null;
}
