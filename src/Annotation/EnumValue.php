<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

use Attribute;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 *
 * Doctrine annotations reader uses `@var` annotations and doesn't know nullability
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
final class EnumValue implements ValueInterface
{
    /**
     * @var string[]
     * @Required()
     */
    public array $enum;

    public function __construct($enum)
    {
        $this->enum = $enum;
    }
}
