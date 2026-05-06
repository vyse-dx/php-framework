<?php

declare(strict_types=1);

namespace Vyse\Framework\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigPathCompilerPass implements CompilerPassInterface
{
    public function process(
        ContainerBuilder $container,
    ): void {
        // If Twig isn't installed in the host project, do nothing.
        if (!$container->hasDefinition('twig.loader.native_filesystem')) {
            return;
        }

        $loader = $container->getDefinition('twig.loader.native_filesystem');

        // Resolve your package's templates directory
        $templatesDir = dirname(__DIR__, 3) . '/templates';

        if (is_dir($templatesDir)) {
            // addMethodCall('addPath') appends the path to the end of the line,
            // guaranteeing the host application's templates will always be checked first.
            $loader->addMethodCall('addPath', [$templatesDir]);
        }
    }
}
