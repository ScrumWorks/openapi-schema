<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\EnumSchemaData;

class EnumSchemaTest extends TestCase
{
    public function testEmptyEnum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Minimal one enum item is required');
        new EnumSchemaData([]);
    }

    public function testInvalidItems(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only strings are allowed for enum properties');
        // @phpstan-ignore-next-line
        new EnumSchemaData(['test', 1]);
    }
}
