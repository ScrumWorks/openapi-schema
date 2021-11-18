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
final class HashmapValue implements ValueInterface
{
    /**
     * @param string[] $requiredProperties
     */
    public function __construct(
        public array $requiredProperties,
        public ValueInterface $itemsSchema,
    ) {
    }
}
