<?php

declare(strict_types=1);

namespace Vyse\Framework;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vyse\Framework\DependencyInjection\Compiler\TwigPathCompilerPass;

class VyseFrameworkBundle extends Bundle
{
    public function build(
        ContainerBuilder $container,
    ): void {
        parent::build($container);

        $container->addCompilerPass(new TwigPathCompilerPass);
    }
}
