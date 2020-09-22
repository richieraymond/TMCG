<?php

namespace UnitTests;

use Config\Constants;
use PHPUnit\Framework\TestCase;
use Controllers\ReadCSVController;

/**
 * CSV File Reader Test
 */
class CSVReaderTest extends TestCase
{
    public function testCSVReader()
    {
        $testFile = Constants::CSV_LOCATION;
        $this->assertFileExists($testFile);
        $this->assertFileIsReadable($testFile);
        $csvReader = new ReadCSVController($testFile);
        $csvRecords = $csvReader->readCSV();
        $this->assertIsArray(json_decode($csvRecords));
        $this->assertGreaterThanOrEqual(0, sizeof(json_decode($csvRecords)));
        $this->assertIsIterable(json_decode($csvRecords));
    }
}
