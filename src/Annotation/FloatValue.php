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
final class FloatValue implements ValueInterface
{
    public function __construct(
        public float $minimum,
        public float $maximum,
        public bool $exclusiveMinimum,
        public bool $exclusiveMaximum,
        public float $multipleOf = 1.0,
    ) {
    }
}
