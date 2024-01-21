<?php

namespace Librarian;

use Dotenv\Dotenv;

class Environment {
    private array $config;

    /**
     * Private constructor to prevent direct instantiation.
     */
    private function __construct(array $config) {
        $this->config = $config;
    }

    /**
     * Factory method to create an instance of Environment.
     * This method uses vlucas/phpdotenv to load environment variables.
     *
     * @return Environment An initialized Environment object.
     */
    public static function factory(): Environment {
        // Load environment variables using Dotenv
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        // Construct config array from environment variables
        $config = [
            'rootPath' => $_ENV['LIBRARIAN_ROOT'] ?? './local',
            // Load other configuration items here
        ];

        return new self($config);
    }

    /**
     * Gets the entire configuration array.
     *
     * @return array The configuration array.
     */
    public function getConfig(): array {
        return $this->config;
    }

    // Other methods...
}
