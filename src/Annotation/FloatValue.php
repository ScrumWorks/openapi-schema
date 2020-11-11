<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class FloatValue implements ValueInterface
{
    /**
     * @var float
     */
    public $minimum;

    /**
     * @var float
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
     * @var float
     */
    public $multipleOf;
}
