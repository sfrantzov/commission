<?php

require __DIR__ . '/../vendor/autoload.php';

use Commission\Base\Config;
use Commission\Commission;
use Commission\Model\ConsoleOutput;
use Commission\Model\CsvReader;

//load config
$config = new Config();

//TODO: We can create Factory and load and output data from different sources
$filePath = $argv[1];
$inputStream = (new CsvReader([
   'filePath' => $filePath
]))->getStream();
$outputStream = (new ConsoleOutput())->getStream();

(new Commission($config))->run($inputStream, $outputStream);

$inputStream->closeStream();
$outputStream->closeStream();
