<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\SchemaParser\Fixture;

class InvalidEntity
{
    public string $aaa;

    /**
     * @var InvalidSubEntity[]
     */
    public array $subEntity;
}
