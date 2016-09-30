<?php

namespace App;

use Monolog\Logger;
use Psr\Log\LoggerInterface;

class Test2
{
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }
}
