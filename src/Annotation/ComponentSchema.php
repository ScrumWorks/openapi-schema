<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

use Attribute;

/**
 * @Annotation
 * @Target("CLASS")
 *
 * Doctrine annotations reader uses `@var` annotations and doesn't know nullability
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class ComponentSchema implements ValueInterface
{
    /**
     * @var string
     */
    public ?string $schemaName = null;
}
