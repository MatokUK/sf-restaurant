<?php

namespace App\Csv;


use App\Csv\Exception\CannotOpenFileException;
use App\Csv\Exception\FileNotFoundException;

class Reader
{
    private $delimiter = ',';
    private $enclosure = '"';
    private $skipHeader = false;
    private $fp;

    /**
     * @param string $filename
     * @param array  $options
     * @throws CannotOpenFileException
     * @throws FileNotFoundException
     */
    public function __construct(string $filename, array $options = [])
    {
        $this->checkFileExists($filename);
        $this->readOptions($options);
        $this->openFile($filename);
    }

    /**
     * @param string $filename
     * @throws FileNotFoundException
     */
    private function checkFileExists(string $filename)
    {
        if (!is_file($filename)) {
            throw new FileNotFoundException(sprintf("File %s does not exists!", $filename));
        }
    }

    private function readOptions(array $options)
    {
        if (isset($options['delimiter'])) {
            $this->delimiter = $options['delimiter'];
        }

        if (isset($options['skip_header'])) {
            $this->skipHeader = $options['skip_header'];
        }

        if (isset($options['enclosure'])) {
            $this->enclosure = $options['enclosure'];
        }
    }

    /**
     * @param string $filename
     * @throws CannotOpenFileException
     */
    private function openFile(string $filename)
    {
        $this->fp = @fopen($filename, 'r');

        if (false === $this->fp) {
            throw new CannotOpenFileException();
        }
    }

    public function setDelimiter(string $delimiter): self
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    public function setEnclosure(string $enclosure): self
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    public function skipHeader(bool $skipHeader): self
    {
        $this->skipHeader = $skipHeader;

        return $this;
    }

    public function readLines()
    {
        if ($this->skipHeader) {
            fgetcsv($this->fp, 0, $this->delimiter, $this->enclosure);
        }

        while (($data = fgetcsv($this->fp, 0, $this->delimiter, $this->enclosure)) !== false) {
            yield $data;
        }
    }

    public function __destruct()
    {
        fclose($this->fp);
    }
}