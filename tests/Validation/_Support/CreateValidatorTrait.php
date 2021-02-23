<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\Validation\_Support;

use ScrumWorks\OpenApiSchema\DiContainer;
use ScrumWorks\OpenApiSchema\Validation\ValueSchemaValidatorInterface;

trait CreateValidatorTrait
{
    public function createValueValidator(): ValueSchemaValidatorInterface
    {
        return (new DiContainer())->getValueSchemaValidator();
    }
}
