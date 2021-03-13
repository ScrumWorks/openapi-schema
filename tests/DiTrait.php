<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests;

use ScrumWorks\OpenApiSchema\DI\DiContainerFactory;

trait DiTrait
{
    /**
     * @template T of object
     * @param class-string<T> $type
     * @return T
     */
    private function getServiceFromContainerByType(string $type): object
    {
        return (new DiContainerFactory(null, true))->createContainer()->getByType($type);
    }
}
