<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class FloatValue
{
    /**
     * @var int|float
     */
    public $minimum;

    /**
     * @var int|float
     */
    public $maximum;

    /**
     * @var bool
     */
    public $exclusiveMinimum;

    /**
     * @var bool
     */
    public $exclusiveMaximum;

    /**
     * @var int|float
     */
    public $multipleOf;
}
