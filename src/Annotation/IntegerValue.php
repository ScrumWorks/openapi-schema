<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

use Attribute;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 *
 * @NamedArgumentConstructor()
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
final class IntegerValue implements ValueInterface
{
    public function __construct(
        public int|null $minimum = null,
        public int|null $maximum = null,
        public bool|null $exclusiveMinimum = null,
        public bool|null $exclusiveMaximum = null,
        public int|null $multipleOf = null,
    ) {
    }
}
