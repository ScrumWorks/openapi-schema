<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\Validation\_Support;

final class TestEntity
{
    public int $cislo;

    /**
     * @var string
     */
    public $retezec;

    public TestSubEntity $objekt;
}
