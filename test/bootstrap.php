<?php

declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;
use Vyse\Toolchain\PhpUnit\Bypass\PhpUnitMutator;

require dirname(__DIR__) . '/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');

$appDebug = $_SERVER['APP_DEBUG'] ?? false;

if (filter_var($appDebug, FILTER_VALIDATE_BOOLEAN)) {
    umask(0000);
}

PhpUnitMutator::enable();
