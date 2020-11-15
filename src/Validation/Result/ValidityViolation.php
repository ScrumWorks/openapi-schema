<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Result;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;
use ScrumWorks\OpenApiSchema\Validation\ValidityViolationInterface;

class ValidityViolation implements ValidityViolationInterface
{
    protected int $violationCode;

    protected string $messageTemplate;

    /**
     * @var mixed[]
     */
    protected array $parameters;

    protected BreadCrumbPathInterface $breadCrumbPath;

    /**
     * @param mixed[] $parameters
     */
    public function __construct(
        int $violationCode,
        string $messageTemplate,
        array $parameters,
        BreadCrumbPathInterface $breadCrumbPath
    ) {
        $this->violationCode = $violationCode;
        $this->messageTemplate = $messageTemplate;
        $this->parameters = $parameters;
        $this->breadCrumbPath = $breadCrumbPath;
    }

    public function getViolationCode(): int
    {
        return $this->violationCode;
    }

    public function getMessageTemplate(): string
    {
        return $this->messageTemplate;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getBreadCrumbPath(): BreadCrumbPathInterface
    {
        return $this->breadCrumbPath;
    }
}
