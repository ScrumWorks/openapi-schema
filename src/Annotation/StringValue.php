<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class StringValue
{
    /**
     * @var int
     */
    public $minLength;

    /**
     * @var int
     */
    public $maxLength;

    /**
     * @var string
     */
    public $format;

    /**
     * @var string
     */
    public $pattern;
}
