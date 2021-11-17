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
final class ArrayValue implements ValueInterface
{
    /**
     * @var int
     */
    public ?int $minItems = null;

    /**
     * @var int
     */
    public ?int $maxItems = null;

    /**
     * @var bool
     */
    public ?bool $uniqueItems = null;

    /**
     * @var \ScrumWorks\OpenApiSchema\Annotation\ValueInterface we can't FQN, because shitty doctrine annotations
     */
    public ?ValueInterface $itemsSchema = null;
}
