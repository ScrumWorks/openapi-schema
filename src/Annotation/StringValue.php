<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

/**
 * @Annotation
 * @\Doctrine\Common\Annotations\Annotation\Target({"PROPERTY", "ANNOTATION"})
 *
 * Doctrine annotations reader uses `@var` annotations and doesn't know nullability
 */
final class StringValue implements ValueInterface
{
    /**
     * @var int
     */
    public ?int $minLength = null;

    /**
     * @var int
     */
    public ?int $maxLength = null;

    /**
     * @var string
     */
    public ?string $format = null;

    /**
     * @var string
     */
    public ?string $pattern = null;
}
