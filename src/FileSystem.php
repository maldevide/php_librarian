<?php

namespace Librarian;

use Smalot\PdfParser\Parser;

/**
 * The FileSystem class handles file operations for the Librarian application.
 * It's responsible for managing the documents directory and accessing the papers.json file.
 */
class FileSystem {
    private string $docsDir;
    private string $papersJsonPath;

    /**
     * Private constructor to ensure the FileSystem is created via the factory method.
     * 
     * @param string $docsDir Path to the documents directory.
     * @param string $papersJsonPath Path to the papers.json file.
     */
    private function __construct(string $docsDir, string $papersJsonPath) {
        $this->docsDir = $docsDir;
        $this->papersJsonPath = $papersJsonPath;
    }

    /**
     * Factory method for creating the FileSystem instance.
     * This method initializes the FileSystem with paths derived from the provided configuration.
     * 
     * @param array $config Configuration array, typically from the Environment object.
     * @return FileSystem The initialized FileSystem object.
     */
    public static function factory(): FileSystem {
        $config = Configuration::getInstance();
        $docsDir = $config->getDocsDir();
        $papersJsonPath = $config->getPapersFile();

        if (!is_dir($docsDir)) {
            throw new \Exception("Documents directory not found at {$docsDir}");
        }
        if (!file_exists($papersJsonPath)) {
            // Create an empty papers.json file with just {}
            file_put_contents($papersJsonPath, '{}');
        }

        return new self($docsDir, $papersJsonPath);
    }

    /**
     * Lists files in the documents directory.
     * 
     * @return array An array of file names in the documents directory.
     */
    public function listDocs(): array {
        $files = [];
        if (is_dir($this->docsDir)) {
            $files = array_diff(scandir($this->docsDir), array('..', '.'));
        }
        return $files;
    }

    /**
     * Reads the papers.json file and returns its content as an associative array.
     * 
     * @return array The content of the papers.json file.
     * @throws \Exception If unable to read the file.
     */
    public function getPapersJson(): array {
        if (!file_exists($this->papersJsonPath)) {
            throw new \Exception("papers.json not found at {$this->papersJsonPath}");
        }

        $content = file_get_contents($this->papersJsonPath);
        if ($content === false) {
            throw new \Exception("Failed to read papers.json at {$this->papersJsonPath}");
        }

        return json_decode($content, true);
    }

    /**
     * Reads the papers.json file and returns the object for the given filename.
     * 
     * @return array The content of the papers.json file.
     * @throws \Exception If unable to read the file.
     */
    public function getPapersJsonFor(string $filename): array {
        $papers = $this->getPapersJson();
        if (!isset($papers[$filename])) {
            throw new \Exception("File not found in papers.json");
        }
        return $papers[$filename];
    }

    /**
     * Sanitize data before writing to the JSON file.
     * 
     * @param mixed $data Data to be sanitized.
     * @return mixed Sanitized data.
     */
    private function sanitizeData($data) {
        if (is_string($data)) {
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        } elseif (is_array($data)) {
            return array_map([$this, 'sanitizeData'], $data);
        }
        // For other data types like booleans and integers, return as is
        return $data;
    }

    /**
     * Updates (patches) the papers.json file with new data and saves it.
     *
     * @param array $updates An associative array of updates.
     * @return bool Returns true if the update and save operation was successful.
     * @throws \Exception If the papers.json file cannot be read or written.
     */
    public function updatePapersJson(array $updates): bool {
        // Sanitize the updates
        $updates = $this->sanitizeData($updates);

        // Read the current papers.json content
        $currentData = $this->getPapersJson();

        // Update the data with new information
        foreach ($updates as $key => $value) {
            $currentData[$key] = $value;  // This could be more complex depending on update needs
        }

        return $this->writePapersJson($currentData);
    }

    /**
     * Removes a paper from the JSON file.
     *
     * @param string $filename The filename of the paper to delete. 
     * @return bool Returns true if the update and save operation was successful.
     * @throws \Exception If the papers.json file cannot be read or written.
     */
    public function removePapersJson(string $filename): bool {
        // Read the current papers.json content
        $currentData = $this->getPapersJson();

        if (!isset($currentData[$filename])) {
            return false; // File not found in papers.json
        }

        // Remove the entry from papers.json
        unset($currentData[$filename]);

        return $this->writePapersJson($currentData);
    }

    /**
     * Takes an array and writes it to the papers.json file.
     * 
     * @param array $data An associative array of data to write to the papers.json file.
     * @return bool Returns true if the write operation was successful.
     * @throws \Exception If the papers.json file cannot be written.
     */
    function writePapersJson(array $data): bool {
        // Sanitize the data
        $data = $this->sanitizeData($data);

        // Encode the data to JSON
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        if ($jsonData === false) {
            throw new \Exception("Error encoding JSON data for papers.json");
        }

        // Write the JSON to the papers.json file
        $result = file_put_contents($this->papersJsonPath, $jsonData);
        if ($result === false) {
            throw new \Exception("Failed to write data to papers.json at {$this->papersJsonPath}");
        }

        return true;
    }

    /**
     * Attempts to open a document file and returns a file handle.
     * 
     * @param string $filename The name of the file in the docs directory.
     * @return resource|null The file handle if successful, or null if the file cannot be opened.
     */
    public function getDocumentFileHandle(string $filename) {
        $filePath = $this->docsDir . '/' . $filename;

        if (!file_exists($filePath)) {
            return null;
        }

        $handle = fopen($filePath, 'rb');  // 'rb' mode for reading binary files
        if ($handle === false) {
            return null;
        }

        return $handle;
    }

    /**
     * Opens a PDF document and returns its text content.
     * 
     * @param string $filename The name of the PDF file in the docs directory.
     * @return string|null The text content of the PDF if successful, or null if unable to read.
     */
    public function getPdfText(string $filename, int $items = 5, int $pages = 1): ?string {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($this->docsDir . '/' . $filename);
            //var_dump($pdf);
            if ($pages > 1) {
                $text = $pdf->getText($pages);
            } elseif ($pages == -1) {
                $text = $pdf->getText();
            } else {
                $text = $pdf->getText(1);
            }
            if ($items <= 0) {
                return $text;
            }
            // Get the first n lines
            $arr = explode("\n", $text);
            $first_n = array_slice($arr, 0, $items);
            return implode(' ', $first_n);
        } catch (\Exception $e) {
            // Handle or log the exception
            return null;
        }
    }

    /**
     * Opens a PDF document and returns its metadata.
     * 
     * @param string $filename The name of the PDF file in the docs directory.
     * @return array|null The metadata of the PDF if successful, or null if unable to read.
     */
    public function getPdfMetadata(string $filename): ?array {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($this->docsDir . '/' . $filename);
            return $pdf->getDetails();
        } catch (\Exception $e) {
            // Handle or log the exception
            return null;
        }
    }

    /**
     * Checks if a file with the given name and size already exists in papers.json.
     *
     * @param string $filename Name of the file to check.
     * @param int $filesize Size of the file in bytes.
     * @return bool Returns true if the file exists, false otherwise.
     */
    public function fileExists(string $filename, int $filesize): bool {
        $papers = $this->getPapersJson();

        foreach ($papers as $paper) {
            if ($paper['filename'] === $filename && $paper['size'] === $filesize) {
                return true;
            }
        }

        return false;
    }

    /**
     * Deletes a paper from the JSON file and the filesystem.
     *
     * @param string $filename The filename of the paper to delete.
     * @return bool Returns true if the file was successfully deleted, false otherwise.
     */
    public function deletePaper(string $filename): bool {
        $papers = $this->getPapersJson();
        
        if (!isset($papers[$filename])) {
            return false; // File not found in papers.json
        }

        // Remove the entry from papers.json
        $result = $this->removePapersJson($filename);

        // Delete the file from the filesystem
        $filePath = $this->docsDir . '/' . $filename;
        if (file_exists($filePath)) {
            return unlink($filePath); // Returns true on success, false on failure
        }

        return $result;
    }
}
