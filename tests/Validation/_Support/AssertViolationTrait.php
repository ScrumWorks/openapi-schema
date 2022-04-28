<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Tests\Validation\_Support;

use PHPUnit\Framework\Assert;
use ScrumWorks\OpenApiSchema\Validation\ValidityViolationInterface;

trait AssertViolationTrait
{
    /**
     * @param array<array<mixed>> $expectedViolations
     * @param ValidityViolationInterface[] $actualViolations
     */
    public function assertViolations(array $expectedViolations, array $actualViolations): void
    {
        $differentViolationCountMsg = \implode("\n", \array_map(
            static fn (ValidityViolationInterface $actualViolation): string => $actualViolation->getMessageTemplate(),
            $actualViolations,
        ));
        Assert::assertCount(\count($expectedViolations), $actualViolations, $differentViolationCountMsg);

        for ($i = 0; $i < \count($expectedViolations); ++$i) {
            $actualViolation = $actualViolations[$i];
            $expectedViolation = $expectedViolations[$i];

            Assert::assertSame($expectedViolation[1], $actualViolation->getMessageTemplate());
            Assert::assertSame($expectedViolation[2], $actualViolation->getParameters());
            Assert::assertSame($expectedViolation[0], $actualViolation->getViolationCode());
            Assert::assertSame($expectedViolation[3], (string) $actualViolation->getBreadCrumbPath());
        }
    }
}
