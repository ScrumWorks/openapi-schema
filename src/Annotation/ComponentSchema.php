<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

use Attribute;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @Target("CLASS")
 *
 * Doctrine annotations reader uses `@var` annotations and doesn't know nullability
 * @NamedArgumentConstructor()
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class ComponentSchema implements ValueInterface
{
    public function __construct(
        public string $schemaName
    ) {
    }
}
