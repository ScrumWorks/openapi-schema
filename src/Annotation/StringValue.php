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
final class StringValue implements ValueInterface
{
    public function __construct(
        public int|null $minLength = null,
        public int|null $maxLength = null,
        public string|null $format = null,
        public string|null $pattern = null,
    ) {
    }
}
