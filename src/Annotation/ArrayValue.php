<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

use Attribute;
use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 * @NamedArgumentConstructor()
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
final class ArrayValue implements ValueInterface
{
    public function __construct(
        public ValueInterface|null $itemsSchema = null,
        public int|null $maxItems = null,
        public int|null $minItems = null,
        public bool $uniqueItems = false,
    ) {
    }
}
