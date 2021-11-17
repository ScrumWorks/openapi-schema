<?php

declare(strict_types=1);

namespace ScrumWorks\OpenApiSchema\DI;

use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;

class DiContainerFactory
{
    private string $tempDir;

    private bool $autoRebuild;

    public function __construct(?string $tempDir = null, bool $autoRebuild = false)
    {
        $this->tempDir = $tempDir ?? sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'ScrumWorksOpenApiSchema';
        $this->autoRebuild = $autoRebuild;
    }

    public function createContainer(): Container
    {
        $containerLoader = new ContainerLoader($this->tempDir, $this->autoRebuild);
        $containerClass = $containerLoader->load(static function (Compiler $compiler): void {
            $compiler->addExtension('openApiSchema', new OpenApiSchemaExtension());
        });

        return new $containerClass();
    }
}
