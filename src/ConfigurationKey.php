<?php

namespace Librarian;

enum ConfigurationKey: string {
    case RootPath = 'rootPath';
    case DocsPath = 'docsPath';
    case PapersJsonPath = 'papersJsonPath';
    // Add other configuration keys as needed
}
