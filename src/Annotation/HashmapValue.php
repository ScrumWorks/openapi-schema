<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

/**
 * @Annotation
 * @\Doctrine\Common\Annotations\Annotation\Target({"PROPERTY", "ANNOTATION"})
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
     * @var \ScrumWorks\OpenApiSchema\Annotation\ValueInterface we can't FQN, because shitty doctrine annotations
     */
    public ?ValueInterface $itemsSchema = null;
}
