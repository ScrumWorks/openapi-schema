<?php

declare(strict_types = 1);

namespace ScrumWorks\OpenApiSchema\Tests\Validation;

final class TestSubEntity
{
    public bool $flag;

    /**
     * @var float[]
     */
    public array $pole;

    /**
     * @var array<int,string>
     */
    public array $hash;
}
