<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class HashmapValue implements ValueInterface
{
    /**
     * @var string[]
     */
    public $requiredProperties;
}
