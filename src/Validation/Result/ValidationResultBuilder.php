<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Result;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;
use ScrumWorks\OpenApiSchema\Validation\ValidationResultInterface;
use ScrumWorks\OpenApiSchema\Validation\ValidityViolationFactoryInterface;
use ScrumWorks\OpenApiSchema\Validation\ValidityViolationInterface;

class ValidationResultBuilder
{
    /**
     * @var ValidityViolationInterface[]
     */
    protected array $validityViolations = [];

    protected ValidityViolationFactoryInterface $validityViolationFactory;

    public function __construct(ValidityViolationFactoryInterface $validityViolationFactory)
    {
        $this->validityViolationFactory = $validityViolationFactory;
    }

    public function addNullableViolation(BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation($this->validityViolationFactory->createNullableViolation($breadCrumbPath));
    }

    public function addTypeViolation(string $type, BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation($this->validityViolationFactory->createTypeViolation($type, $breadCrumbPath));
    }

    public function addRequiredViolation(BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation($this->validityViolationFactory->createRequiredViolation($breadCrumbPath));
    }

    public function addUnexpectedViolation(BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation($this->validityViolationFactory->createUnexpectedViolation($breadCrumbPath));
    }

    public function addMinItemsViolation(int $min, BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation($this->validityViolationFactory->createMinItemsViolation($min, $breadCrumbPath));
    }

    public function addMaxItemsViolation(int $max, BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation($this->validityViolationFactory->createMaxItemsViolation($max, $breadCrumbPath));
    }

    public function addUniqueViolation(BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation($this->validityViolationFactory->createUniqueViolation($breadCrumbPath));
    }

    /**
     * @param string[] $choices
     */
    public function addEnumViolation(array $choices, BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation($this->validityViolationFactory->createEnumViolation($choices, $breadCrumbPath));
    }

    public function addMinimumViolation(float $minimum, BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation($this->validityViolationFactory->createMinimumViolation($minimum, $breadCrumbPath));
    }

    public function addExclusiveMinimumViolation(float $minimum, BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation(
            $this->validityViolationFactory->createExclusiveMinimumViolation($minimum, $breadCrumbPath)
        );
    }

    public function addMaximumViolation(float $maximum, BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation($this->validityViolationFactory->createMaximumViolation($maximum, $breadCrumbPath));
    }

    public function addExclusiveMaximumViolation(float $maximum, BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation(
            $this->validityViolationFactory->createExclusiveMaximumViolation($maximum, $breadCrumbPath)
        );
    }

    public function addMultipleOfViolation(float $multiplier, BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation(
            $this->validityViolationFactory->createMultipleOfViolation($multiplier, $breadCrumbPath)
        );
    }

    public function addMinLengthViolation(int $minLength, BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation(
            $this->validityViolationFactory->createMinLengthViolation($minLength, $breadCrumbPath)
        );
    }

    public function addMaxLengthViolation(int $maxLength, BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation(
            $this->validityViolationFactory->createMaxLengthViolation($maxLength, $breadCrumbPath)
        );
    }

    public function addFormatViolation(string $format, BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation($this->validityViolationFactory->createFormatViolation($format, $breadCrumbPath));
    }

    public function addPatternViolation(string $pattern, BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation($this->validityViolationFactory->createPatternViolation($pattern, $breadCrumbPath));
    }

    public function addOneOfNoMatchViolation(BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation($this->validityViolationFactory->createOneOfNoMatchViolation($breadCrumbPath));
    }

    public function addOneOfAmbiguousViolation(BreadCrumbPathInterface $breadCrumbPath): self
    {
        return $this->addViolation($this->validityViolationFactory->createOneOfAmbiguousViolation($breadCrumbPath));
    }

    public function mergeResult(ValidationResultInterface $validationResult): self
    {
        return $this->mergeViolations($validationResult->getViolations());
    }

    /**
     * @param ValidityViolationInterface[] $violations
     */
    public function mergeViolations(array $violations): self
    {
        foreach ($violations as $violation) {
            $this->addViolation($violation);
        }
        return $this;
    }

    public function createResult(): ValidationResultInterface
    {
        return new ValidationResult(array_values($this->validityViolations));
    }

    protected function addViolation(ValidityViolationInterface $validityViolation): self
    {
        $this->validityViolations[serialize($validityViolation)] = $validityViolation;
        return $this;
    }
}
