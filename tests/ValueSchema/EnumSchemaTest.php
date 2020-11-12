<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\ValueSchema\EnumSchema;

class EnumSchemaTest extends TestCase
{
    public function testEmptyEnum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Minimal one enum item is required');
        new EnumSchema([]);
    }

    public function testInvalidItems(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only strings are allowed for enum properties');
        // @phpstan-ignore-next-line
        new EnumSchema(['test', 1]);
    }
}
