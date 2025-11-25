<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Mcp\Server;
use Mcp\Server\Transport\StdioTransport;

$server = Server::builder()
    ->setServerInfo('Code Quality Prompts Server', '0.1.0')
    ->setDiscovery(__DIR__ . '/src', ['.'])
    ->build();

$transport = new StdioTransport();
$server->run($transport);
