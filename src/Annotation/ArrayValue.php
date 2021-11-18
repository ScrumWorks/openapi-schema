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
        public int $maxItems,
        public ValueInterface $itemsSchema,
        public int|null $minItems = null,
        public bool $uniqueItems = false,
    ) {
    }
}
