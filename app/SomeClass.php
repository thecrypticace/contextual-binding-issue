<?php

namespace App;

use Psr\Log\LoggerInterface;

class SomeClass
{
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }
}
