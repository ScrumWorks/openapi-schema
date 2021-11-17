<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

use Attribute;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 *
 * Doctrine annotations reader uses `@var` annotations and doesn't know nullability
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
final class Union implements ValueInterface
{
    /**
     * @var string
     */
    public ?string $discriminator = null;

    /**
     * @var array<string, string>
     */
    public ?array $mapping = null;

    /**
     * @var \ScrumWorks\OpenApiSchema\Annotation\ValueInterface[] we can't FQN, because shitty doctrine annotations
     */
    public ?array $types = null;
}
