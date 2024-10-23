<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LoggingService
{
    /**
     * Centralized log info method that logs to the specified channel.
     *
     * @param string $channel  The logging channel (user, book, borrowing, etc.)
     * @param string $message  The log message
     * @param array  $context  Additional context data (optional)
     */
    public function logInfo($channel, $message, $context = null)
    {
        if(is_null($context)) {
            $context = [];
        }elseif(!is_array($context)) {
            $context = [$context];
        }
        Log::channel($channel)->info($message, $context);
    }

    /**
     * Log error message to the error channel.
     *
     * @param string $message
     * @param array  $context
     */
    public function logError($message, array $context = null)
    {
        if(is_null($context)) {
            $context = [];
        }elseif(!is_array($context)) {
            $context = [$context];
        }
        $this->logInfo('error', $message, $context);
    }
}
