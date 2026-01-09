<?php

/**
 * Change the default error handler to catch them as exceptions
 */
function exceptions_error_handler($severity, $message, $filename, $lineno)
{
    // Ignorer les dépréciations (E_DEPRECATED et E_USER_DEPRECATED)
    if (($severity & error_reporting()) === 0 || in_array($severity, [E_DEPRECATED, E_USER_DEPRECATED], true)) {
        return false; 
    }

    throw new ErrorException($message, 0, $severity, $filename, $lineno);
}
set_error_handler('exceptions_error_handler');

/**
 * Load .env file as environment variables
 */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();
