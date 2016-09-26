<?php

namespace App;

use Psr\Log\LoggerTrait;
use Psr\Log\LoggerInterface;

class SomeClassLogger implements LoggerInterface
{
    use LoggerTrait;

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        echo "I swear I'm going to log this somewhere";
    }
}
