<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class EnumValue implements ValueInterface
{
    /**
     * @var string[]
     * @Required()
     */
    public $enum;
}
