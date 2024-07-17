<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\ValueSchema;

use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Exception\InvalidArgumentException;
use ScrumWorks\OpenApiSchema\ValueSchema\Data\StringSchemaData;

class StringSchemaTest extends TestCase
{
    public function testInvalidMinLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value -1 for argument 'minLength'");
        new StringSchemaData(-1);
    }

    public function testInvalidMaxLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value -1 for argument 'maxLength'");
        new StringSchemaData(null, -1);
    }

    public function testExceedingMaxLength(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid value 90 for argument 'maxLength'");
        new StringSchemaData(100, 90);
    }
}
