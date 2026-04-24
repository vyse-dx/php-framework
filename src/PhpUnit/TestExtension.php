<?php

declare(strict_types=1);

namespace Vyse\Framework\PhpUnit;

use PHPUnit\Event\TestSuite\Started;
use PHPUnit\Event\TestSuite\StartedSubscriber;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use RuntimeException;
use Symfony\Component\Process\Process;

final class TestExtension implements Extension
{
    public function bootstrap(
        Configuration $configuration,
        Facade $facade,
        ParameterCollection $parameters,
    ): void {
        $facade->registerSubscriber(new class ($this) implements StartedSubscriber {
            public function __construct(
                private TestExtension $extension,
            ) {
            }

            public function notify(
                Started $event,
            ): void {
                $name = $event->testSuite()->name();
                static $done = false;

                if ($done) {
                    return;
                }

                if (!in_array($name, ['Data', 'Web'], true)
                    || str_contains($name, 'test/Data')
                    || str_contains($name, 'test/Web')
                ) {
                    return;
                }

                $this->extension->runDatabaseReset();
                $done = true;
            }
        });
    }

    public function runDatabaseReset(): void
    {
        $commands = [
            ['bin/console', 'doctrine:database:drop', '--force', '--if-exists', '--env=test'],
            ['bin/console', 'doctrine:database:create', '--env=test'],
            ['bin/console', 'doctrine:migrations:migrate', '--no-interaction', '--env=test'],
            ['bin/console', 'doctrine:fixtures:load', '--no-interaction', '--env=test'],
        ];

        $projectRoot = dirname(__DIR__, 2);

        foreach ($commands as $args) {
            $process = new Process($args);
            $process->setWorkingDirectory($projectRoot);
            $process->setTimeout(300);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new RuntimeException(
                    sprintf("Command failed: %s\nError: %s", implode(' ', $args), $process->getErrorOutput()),
                );
            }
        }
    }
}
