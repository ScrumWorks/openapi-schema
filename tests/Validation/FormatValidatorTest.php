<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\Validation;

use Iterator;
use PHPUnit\Framework\TestCase;
use ScrumWorks\OpenApiSchema\Validation\Validator\Format\DateTimeValidator;
use ScrumWorks\OpenApiSchema\Validation\Validator\Format\DateValidator;

class FormatValidatorTest extends TestCase
{
    /**
     * @dataProvider dpTestDateTime
     */
    public function testDateTime(bool $expectedResult, string $string): void
    {
        $validator = new DateTimeValidator();
        $this->assertSame($expectedResult, $validator->hasValidFormat($string));
    }

    public function dpTestDateTime(): Iterator
    {
        yield [false, ''];
        yield [false, 'substring 2020-02-03T03:05:06 not match'];
        yield [false, '2a20-02-03T03:05:06'];
        yield [false, '2020-02-03 03:05:06'];
        yield [false, '2020-02-03T03:05:06 00:00'];

        yield [true, '2020-02-03T03:05:06'];
        yield [true, '2020-02-03t03:05:06'];
        yield [true, '2020-02-03T03:05:06Z'];
        yield [true, '2020-02-03T03:05:06z'];
        yield [true, '2020-02-03T03:05:06+01:00'];
        yield [true, '2020-02-03T03:05:06-01:00'];
        yield [true, '2020-02-03T03:05:06+00:00'];
        yield [true, '2020-02-03T03:05:06.000133'];
        yield [true, '2020-02-03T03:05:06.000133Z'];
        yield [true, '2020-02-03T03:05:06.000133+00:30'];
        yield [true, '2020-02-03T03:05:06.000133-01:30'];
        yield [true, '2020-02-03T03:05:06.000133+00:00'];
    }

    /**
     * @dataProvider dpTestDate
     */
    public function testDate(bool $expectedResult, string $string): void
    {
        $validator = new DateValidator();
        $this->assertSame($expectedResult, $validator->hasValidFormat($string));
    }

    public function dpTestDate(): Iterator
    {
        yield [false, ''];
        yield [false, 'substring 2020-02-03 not match'];
        yield [false, '2a20-02-03'];
        yield [false, '2020-02-03 03:05:06'];
        yield [false, '2020-02-03T03:05:06'];
        yield [false, '2020/02/03'];
        yield [false, '2.2.2020'];

        yield [true, '2020-02-03'];
    }
}
