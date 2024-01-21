<?php

namespace Librarian;

class Configuration {
    private static ?Configuration $instance = null;
    private array $config;

    private function __construct() {
        $this->config = [
           ConfigurationKey::RootPath->value => "local",
           ConfigurationKey::DocsPath->value => 'docs',
           ConfigurationKey::PapersJsonPath->value => 'papers.json',
        ];
    }

    public function setValue(ConfigurationKey $key, string $value): void {
        $this->config[$key->value] = $value;
    }

    public static function getInstance(): Configuration {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function get(ConfigurationKey $key) {
        return $this->config[$key->value] ?? null;
    }

    public function getDocsDir() : string {
        $root = $this->config[ConfigurationKey::RootPath->value];
        $doc = $this->config[ConfigurationKey::DocsPath->value];
        return __DIR__ . '/../' . $root .'/'. $doc;
    }

    public function getPapersFile(): string {
        $root = $this->config[ConfigurationKey::RootPath->value];
        $file = $this->config[ConfigurationKey::PapersJsonPath->value];
        return __DIR__ . '/../' . $root .'/'. $file;
    }

    public static function set(ConfigurationKey $key, string $config) {
        self::getInstance()->setValue($key, $config);
    }
}
