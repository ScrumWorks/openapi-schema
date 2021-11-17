<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

/**
 * @Annotation
 * @\Doctrine\Common\Annotations\Annotation\Target("PROPERTY")
 *
 * Doctrine annotations reader uses `@var` annotations and doesn't know nullability
 */
final class Property
{
    /**
     * @var string
     */
    public ?string $description = null;

    /**
     * @var bool
     */
    public ?bool $required = null;

    /**
     * @var bool
     */
    public ?bool $nullable = null;
}
