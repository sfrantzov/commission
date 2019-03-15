<?php

namespace Commission\Model;

use Assert\Assert;
use Commission\Base\Model;
use Commission\Exception\StreamException;
use Commission\Model\Interfaces\InputStreamInterface;

/**
 * Read CSV file
 */
class CsvReader extends Model implements InputStreamInterface
{
    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var resource
     */
    protected $fileHandle;

    /**
     * Get filePath
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set filePath
     *
     * @param string $filePath
     */
    public function setFilePath($filePath)
    {
        Assert::that($filePath)->notEmpty("File is required");
        Assert::that($filePath)->file("Invalid file");
        $this->filePath = $filePath;
    }

    /**
     * @return InputStreamInterface
     * @throws StreamException
     */
    public function getStream()
    {
        $this->fileHandle = fopen($this->filePath, "r");
        if (!$this->fileHandle) {
            throw new StreamException("File can't open");
        }
        return $this;
    }

    /**
     * @return Input|null
     */
    public function getRow()
    {
        $row = fgetcsv($this->fileHandle, 0, ",");
        if ($row) {
            return new Input([
                'date' => trim($row[0]),
                'userId' => (int) trim($row[1]),
                'userType' => trim($row[2]),
                'operationType' => trim($row[3]),
                'amount' => trim($row[4]),
                'currency' => trim($row[5])
            ]);
        }
    }

    /**
     * Close file handle
     */
    public function closeStream()
    {
       fclose($this->fileHandle);
    }
}
