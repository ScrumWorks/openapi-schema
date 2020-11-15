<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation;

interface ValidityViolationInterface
{
    /**
     * Returns unique integer identifier of violation
     */
    public function getViolationCode(): int;

    /**
     * Returns description of violation with placeholders instead of parameters
     */
    public function getMessageTemplate(): string;

    /**
     * @return mixed[]
     */
    public function getParameters(): array;

    public function getBreadCrumbPath(): BreadCrumbPathInterface;
}
