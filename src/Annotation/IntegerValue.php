<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class IntegerValue
{
    /**
     * @var int
     */
    public $minimum;

    /**
     * @var int
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
     * @var int
     */
    public $multipleOf;
}
