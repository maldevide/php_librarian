<?php

namespace Librarian;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log {
    private static ?Logger $instance = null;

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct() {
        // Intentionally left empty
    }

    /**
     * Returns the logger instance, creating it if it does not exist.
     *
     * @return Logger The logger instance.
     */
    public static function getInstance(): Logger {
        if (self::$instance === null) {
            $logsDirPath = __DIR__ . '/logs';
            self::createLogsDirectory($logsDirPath);

            self::$instance = new Logger('librarian');
            $logFilePath = $logsDirPath . '/app.log';
            self::$instance->pushHandler(new StreamHandler($logFilePath, Logger::DEBUG));
        }

        return self::$instance;
    }

    /**
     * Creates the logs directory if it does not exist.
     *
     * @param string $logsDirPath Path to the logs directory.
     */
    private static function createLogsDirectory(string $logsDirPath): void {
        if (!file_exists($logsDirPath)) {
            mkdir($logsDirPath, 0777, true);
        }
    }

    /**
     * Logs an info message.
     *
     * @param string $message The log message.
     * @param string|null $component The component from which the log is coming.
     */
    public static function i(string $message, ?string $component = null): void {
        self::logMessage(Logger::INFO, $message, $component);
    }

    /**
     * Logs a warning message.
     *
     * @param string $message The log message.
     * @param string|null $component The component from which the log is coming.
     */
    public static function w(string $message, ?string $component = null): void {
        self::logMessage(Logger::WARNING, $message, $component);
    }

    /**
     * Logs an error message.
     *
     * @param string $message The log message.
     * @param string|null $component The component from which the log is coming.
     */
    public static function e(string $message, ?string $component = null): void {
        self::logMessage(Logger::ERROR, $message, $component);
    }

    /**
     * Helper method to log a message.
     *
     * @param int $level The log level.
     * @param string $message The log message.
     * @param string|null $component The component from which the log is coming.
     */
    private static function logMessage(int $level, string $message, ?string $component): void {
        $logger = self::getInstance();
        $message = $component ? "[$component] $message" : $message;
        $logger->addRecord($level, $message);
    }
}
