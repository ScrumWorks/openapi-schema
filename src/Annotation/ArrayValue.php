<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class ArrayValue implements ValueInterface
{
    /**
     * @var int
     */
    public $minItems;

    /**
     * @var int
     */
    public $maxItems;

    /**
     * @var bool
     */
    public $uniqueItems;
}
