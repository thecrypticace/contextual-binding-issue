<?php

namespace App;

use Illuminate\Log\Writer;
use Psr\Log\LoggerInterface;

class Test3
{
    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }
}
