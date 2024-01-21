<?php

namespace Librarian;

use Librarian\FileSystem;
use Librarian\Configuration;

class DocumentUpload {
    private FileSystem $fileSystem;
    private string $docsDir;
    private int $maxFileSize;

    public function __construct(FileSystem $fileSystem) {
        $this->fileSystem = $fileSystem;
        $config = Configuration::getInstance();
        $this->docsDir = $config->getDocsDir();
        $this->maxFileSize = 2**25;
        $this->maxFileSize = 1000000; // TODO move to config
    }

    public function uploadFile(array $file, string $method = 'move_upload'): array {
        // Check for errors in the file upload
        if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
            return ['error' => "File is too large."];
        } elseif ($file['error'] !== UPLOAD_ERR_OK) {
            return ['error' => "An error occurred during file upload."];
        } elseif ($file['type'] !== 'application/pdf') {
            return ['error' => "The file must be a PDF."];
        }

        $filename = basename($file['name']);
        $filesize = $file['size'];
        if ($this->fileSystem->fileExists($filename, $filesize)) {
            return ['error' => "File already exists."];
        }

        list($filePrefix, $fileExt) = $this->fileExtension($filename);
        $filePrefix = $this->sanitizeFilename($filePrefix);
        $destination = $this->docsDir . '/' . $filePrefix . '_' . uniqid() . '.' . $fileExt;

        $fromPath = $file['tmp_name'];
        if ($method === 'copy') {
            $result = copy($fromPath, $destination);
        } elseif ($method === 'move_upload') {
            $result = move_uploaded_file($fromPath, $destination);
        } elseif ($method === 'move') {
            $result = rename($fromPath, $destination);
        } else {
            throw new \Exception("Invalid method: {$method}");
        }
        if ($result) {
            $pdfFile = basename($destination);
            if ($file['size'] <= $this->maxFileSize) {
                $clip = $this->fileSystem->getPdfText($pdfFile, 15, 3);
                $status = "Parsed";
            } else {
                $clip = '';
                $status = "Large File";
            }
            $metadata = $this->fileSystem->getPdfMetadata($pdfFile);
            $uploadStatus = [
                "clip" => $clip,
                "filename" => $filename,
                "filetype" => $file['type'],
                "size" => $file['size'],
                "uploaded_on" => date("Y-m-d", time()),
                "abstract" => "",
                "summary" => "",
                "tags" => [],
                "notes" => "",
                "visible" => true,
                "status" => $status
            ];
            if ($metadata) {
                // add metadata to papers.json
                $uploadStatus = array_merge($uploadStatus, $metadata);
            }
            $this->fileSystem->updatePapersJson([$pdfFile => $uploadStatus]);
            return ['success' => "File uploaded successfully!", 'status' => $uploadStatus];
        } else {
            return ['error' => "Failed to save the uploaded file."];
        }
    }

    private function sanitizeFilename($filePrefix) {
        $filePrefix = preg_replace('/[^a-zA-Z0-9.\-]/', ' ', $filePrefix);
        $filePrefix = preg_replace('/\s+/', '_', $filePrefix);
        return $filePrefix;
    }

    static function getMaximumUploadSize() {
        $maxUpload = ini_get('upload_max_filesize');
        $maxPost = ini_get('post_max_size');
        // Convert sizes to bytes and return the smaller of two
        print_r([$maxUpload, $maxPost]);
        return min(self::shorthandToBytes($maxUpload), self::shorthandToBytes($maxPost));
    }

    // Convert shorthand byte value to bytes
    static function shorthandToBytes($sizeStr) {
        $units = ['B'=>0, 'K'=>10, 'M'=>20, 'G'=>30, 'T'=>40];
        $unit = strtoupper(preg_replace('/[^BKMGT]/', '', $sizeStr));
        $bytes = preg_replace('/[^0-9.]/', '', $sizeStr);
        return $bytes * pow(2, $units[$unit]);
    }

    static function fileExtension($name) {
        $n = strrpos($name, '.');
        $ext = ($n === false) ? '' : substr($name, $n+1);
        // capture the non-extension part of the filename
        // by trimming the $ext length off the end
        $extlen = strlen($ext);
        $name = substr($name, 0, -$extlen - 1);
        // Return both
        return [$name, $ext];
    }


    // ... any other helper methods you need ...
}
