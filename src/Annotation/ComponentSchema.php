<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 *
 * Doctrine annotations reader uses `@var` annotations and doesn't know nullability
 */
final class ComponentSchema implements ValueInterface
{
    /**
     * @var string
     */
    public ?string $schemaName = null;
}
