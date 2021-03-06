<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 *
 * Doctrine annotations reader uses `@var` annotations and doesn't know nullability
 */
final class HashmapValue implements ValueInterface
{
    /**
     * @var string[]
     */
    public array $requiredProperties = [];

    /**
     * @codingStandardsIgnoreLine
     * @var \ScrumWorks\OpenApiSchema\Annotation\ValueInterface we can't FQN, because shitty doctrine annotations
     */
    public ?ValueInterface $itemsSchema = null;
}
