<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\Validation\Result;

use ScrumWorks\OpenApiSchema\Validation\BreadCrumbPathInterface;

final class BreadCrumbPath implements BreadCrumbPathInterface
{
    /**
     * @var string[]
     */
    private $breadCrumbs = [];

    public function __toString()
    {
        return \implode('.', $this->breadCrumbs);
    }

    public function withNextBreadCrumb(string $breadCrumb): self
    {
        $breadCrumbPath = clone $this;
        $breadCrumbPath->breadCrumbs[] = $breadCrumb;
        return $breadCrumbPath;
    }

    public function withIndex(int $index): self
    {
        $breadCrumbPath = clone $this;
        $breadCrumbPath->breadCrumbs[] = (string) \array_pop($breadCrumbPath->breadCrumbs) . "[{$index}]";
        return $breadCrumbPath;
    }
}
