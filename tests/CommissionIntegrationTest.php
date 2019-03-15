<?php

namespace Commission\Tests;

use Commission\Commission;
use Commission\Model\ArrayOutput;
use Commission\Model\CsvReader;

class CommissionIntegrationTest extends AbstractTest
{
    public function test_integration_test()
    {
        $this->setConfig();
        $inputStream = (new CsvReader([
            'filePath' => __DIR__ . '/inputTest.csv'
        ]))->getStream();
        $outputStream = new ArrayOutput();

        (new Commission())->run($inputStream, $outputStream->getStream());

        $expected = [
            '0.60', '3.00', '0.00', '0.50', '9.00', '0', '0.76', '0.30', '0.30', '0.50', '0.00', '0.00', '8642'
        ];

        $this->assertEquals($expected, $outputStream->getItems());
    }
}
