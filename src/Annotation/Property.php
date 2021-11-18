<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

use Attribute;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @Target("PROPERTY")
 *
 * @NamedArgumentConstructor()
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Property
{
    public function __construct(
        public bool|null $required = null,
        public bool|null $nullable = null,
        public string|null $description = null,
    ) {
    }
}
