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
final class Union implements ValueInterface
{
    /**
     * @param array<string, string>|null $mapping
     * @param \ScrumWorks\OpenApiSchema\Annotation\ValueInterface[] $types we can't FQN, because shitty doctrine annotations
     */
    public function __construct(
        public array $types = [],
        public array|null $mapping = null,
        public string|null $discriminator = null,
    ) {
    }
}
