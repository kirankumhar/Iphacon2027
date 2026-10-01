<?php

namespace Sbiepay\utils;

class Logger
{
    /**
     * Log a message and optional data to the error log (stdout / terminal).
     * Respects a boolean flag to enable/disable logging.
     *
     * @param bool $enabled
     * @param string $message
     * @param mixed $data
     * @return void
     */
    public static function log(bool $enabled, string $message, $data = null): void
    {
        if (! $enabled) {
            return;
        }

        // Colorized prefix to keep parity with old helper
        error_log("\033[94m[LOG ] $message\033[0m");

        if ($data !== null) {
            if (is_array($data) || is_object($data)) {
                error_log(json_encode($data, JSON_PRETTY_PRINT));
            } else {
                error_log($data);
            }
        }
    }
}
