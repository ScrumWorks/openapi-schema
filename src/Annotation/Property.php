<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class Property
{
    /**
     * @var string
     */
    public $description;

    /**
     * @var bool
     */
    public $required;
}
