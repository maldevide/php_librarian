<?php

use Librarian\Configuration;
use Librarian\ConfigurationKey;
use PHPUnit\Framework\TestCase;
use Librarian\FileSystem;

/**
 * Tests for the FileSystem class of the Librarian application.
 * This class tests the file operations such as listing documents and managing the papers.json file.
 */
class FileSystemTest extends TestCase {

    private FileSystem $fileSystem;

    protected function setUp(): void {
        parent::setUp();
        // Set the rootPath to the test_resources directory relative to this test file's directory
        $config = ['rootPath' => __DIR__ . 'test_resources'];
        Configuration::set(ConfigurationKey::RootPath, 'test_resources');
        $this->fileSystem = FileSystem::factory();
    }

    /**
     * Test to ensure that documents can be listed from the docs directory.
     */
    public function testListDocs() {
        $docs = $this->fileSystem->listDocs();
        $this->assertIsArray($docs);
        // Further assertions can be based on the expected contents of the test docs directory
    }

    /**
     * Test to ensure that the papers.json file can be read correctly.
     */
    public function testGetPapersJson() {
        $papers = $this->fileSystem->getPapersJson();
        $this->assertIsArray($papers);
        // Further assertions can check for specific contents of the papers.json file
    }

    /**
     * Test to ensure that the papers.json file can be updated correctly.
     */
    public function testUpdatePapersJson() {
        $updates = ['test_paper.pdf' => ['clip' => 'Updated Test Paper', 'author' => 'Updated Author']];
        $result = $this->fileSystem->updatePapersJson($updates);
        $this->assertTrue($result);

        // Optionally, re-read papers.json to verify updates
        $updatedPapers = $this->fileSystem->getPapersJson();
        $this->assertArrayHasKey('test_paper.pdf', $updatedPapers);
        $this->assertEquals('Updated Test Paper', $updatedPapers['test_paper.pdf']['clip']);
    }

    /**
     * Test to ensure that the getPdfText method returns correct text from a PDF file.
     */
    public function testGetPdfText() {
        $samplePdfFilename = 'test_paper.pdf'; // Replace with an actual test file name
        $expectedText = 'arXiv:2312.06937v1  [cs.LG]  12 Dec 2023 Can a Transformer Represent a Kalman Filter? Gautam Goel Peter Bartlett Simons Institute, UC Berkeley Abstract'; // Replace with expected text

        $pdfText = $this->fileSystem->getPdfText($samplePdfFilename);

        $this->assertNotNull($pdfText, 'PDF text should not be null');
        $this->assertIsString($pdfText, 'PDF text should be a string');
        $this->assertStringContainsString($expectedText, $pdfText, 'PDF text should contain the expected text');
    }


    protected function tearDown(): void {
        parent::tearDown();
        // Clean up or reset anything necessary after each test
    }
}
