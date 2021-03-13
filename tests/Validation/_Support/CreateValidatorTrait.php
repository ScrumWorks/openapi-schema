<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\Validation\_Support;

use ScrumWorks\OpenApiSchema\Tests\DiTrait;
use ScrumWorks\OpenApiSchema\Validation\ValueSchemaValidatorInterface;

trait CreateValidatorTrait
{
    use DiTrait;

    public function createValueValidator(): ValueSchemaValidatorInterface
    {
        return $this->getServiceFromContainerByType(ValueSchemaValidatorInterface::class);
    }
}
