<?php

namespace App\Tests\Csv;

use App\Csv\Exception\CannotOpenFileException;
use App\Csv\Exception\FileNotFoundException;
use App\Csv\Reader;
use PHPUnit\Framework\TestCase;

class ReaderTest extends TestCase
{
    private const UNREADABLE_FILE_PATH = __DIR__.'/fixture/unreadable.csv';

    private $csvData = [
        0 => ['Symfony', 'PHP'],
        1 => ['PHPUnit', 'PHP'],
        2 => ['JUnit', 'Java'],
    ];

    public static function tearDownAfterClass(): void
    {
        unlink(self::UNREADABLE_FILE_PATH);
    }

    /**
     * @depends testFluentInterface
     */
    public function testSkipHeaderComaSeparated()
    {
        $csvReader = new Reader(__DIR__ . '/fixture/header_coma.csv');
        $csvReader->skipHeader(true)
                  ->setDelimiter(',');

        $csvContent = $this->readWholeFile($csvReader);

        $this->assertEquals($this->csvData, $csvContent);
    }

    /**
     * @depends testFluentInterface
     */
    public function testSkipHeaderDotSeparated()
    {
        $csvReader = new Reader(__DIR__ . '/fixture/header_dot.csv');
        $csvReader->skipHeader(true)
                  ->setDelimiter('.');

        $csvContent = $this->readWholeFile($csvReader);

        $this->assertEquals($this->csvData, $csvContent);
    }

    /**
     * @depends testFluentInterface
     */
    public function testDontSkipHeaderEnclosed()
    {
        $csvReader = new Reader(__DIR__ . '/fixture/enclosed.csv');
        $csvReader->skipHeader(false)
            ->setEnclosure('"');

        $csvContent = $this->readWholeFile($csvReader);

        $this->assertEquals($this->csvData, $csvContent);
    }

    private function readWholeFile($csvReader): array
    {
        $csvContent = [];
        foreach ($csvReader->readLines() as $line) {
            $csvContent[] = $line;
        }

        return $csvContent;
    }

    public function testWrongFilePathThrowsException()
    {
        $this->expectException(FileNotFoundException::class);

        $csvReader = new Reader(__DIR__.'/foobar.csv');
    }

    public function testUnreadableFileThrowsException()
    {
        $this->expectException(CannotOpenFileException::class);

        fopen(self::UNREADABLE_FILE_PATH, 'w');
        chmod(self::UNREADABLE_FILE_PATH, 0000);

        $csvReader = new Reader(self::UNREADABLE_FILE_PATH);
    }

    public function testFluentInterface()
    {
        $csvReader = new Reader(__DIR__.'/fixture/empty.csv');

        $instance = $csvReader->setDelimiter(';');
        $this->assertInstanceOf(Reader::class, $instance);

        $instance = $csvReader->skipHeader(true);
        $this->assertInstanceOf(Reader::class, $instance);

        $instance = $csvReader->setEnclosure(true);
        $this->assertInstanceOf(Reader::class, $instance);
    }
}