<?php

namespace Sbiepay\exception;

use Exception;

class SBIEpayException extends Exception
{
    public function __construct($arg1 = null, $arg2 = null, $arg3 = null)
    {
        // Handle different constructor patterns, similar to Java overloads

        // SBIEpayException(String message)
        if (is_string($arg1) && $arg2 === null && $arg3 === null) {
            parent::__construct($arg1);
        }

        // SBIEpayException(String message, Throwable cause)
        else if (is_string($arg1) && $arg2 instanceof Exception && $arg3 === null) {
            parent::__construct($arg1, 0, $arg2);
        }

        // SBIEpayException(int statusCode, String message)
        else if (is_int($arg1) && is_string($arg2) && $arg3 === null) {
            $message = "Status Code: {$arg1}\nServer response: {$arg2}";
            parent::__construct($message);
        }

        // SBIEpayException(int statusCode, String message, Throwable cause)
        else if (is_int($arg1) && is_string($arg2) && $arg3 instanceof Exception) {
            $message = "Status Code: {$arg1}\nServer response: {$arg2}";
            parent::__construct($message, 0, $arg3);
        }

        // SBIEpayException(Throwable cause)
        else if ($arg1 instanceof Exception && $arg2 === null && $arg3 === null) {
            parent::__construct($arg1->getMessage(), 0, $arg1);
        }

        // Default fallback
        else {
            parent::__construct("Something went wrong with exception!");
        }
    }
}
