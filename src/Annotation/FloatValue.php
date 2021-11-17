<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

/**
 * @Annotation
 * @\Doctrine\Common\Annotations\Annotation\Target({"PROPERTY", "ANNOTATION"})
 *
 * Doctrine annotations reader uses `@var` annotations and doesn't know nullability
 */
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
