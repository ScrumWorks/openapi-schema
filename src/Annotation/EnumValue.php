<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @\Doctrine\Common\Annotations\Annotation\Target({"PROPERTY", "ANNOTATION"})
 *
 * Doctrine annotations reader uses `@var` annotations and doesn't know nullability
 */
final class EnumValue implements ValueInterface
{
    /**
     * @var string[]
     * @Required()
     */
    public array $enum;
}
