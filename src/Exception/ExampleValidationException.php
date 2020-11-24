<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Exception;

use ScrumWorks\OpenApiSchema\Validation\ValidationResultInterface;
use Throwable;

class ExampleValidationException extends LogicException
{
    private ?ValidationResultInterface $validationResult;

    public function __construct(
        $message = '',
        $code = 0,
        ?Throwable $previous = null,
        ?ValidationResultInterface $validationResult = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->validationResult = $validationResult;
    }

    public function getValidationResult(): ?ValidationResultInterface
    {
        return $this->validationResult;
    }
}
