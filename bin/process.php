<?php

require __DIR__ . '/../vendor/autoload.php';

use Commission\Base\Util;
use Commission\Commission;
use Commission\Model\CsvReader;
use Commission\Collection\ExchangeRateCollection;
use Maba\Component\Math\BcMath;
use Maba\Component\Math\Math;
use Maba\Component\Math\NumberFormatter;
use Maba\Component\Math\NumberValidator;
use Maba\Component\Monetary\Factory\MoneyFactory;
use Maba\Component\Monetary\Formatting\MoneyFormatter;
use Maba\Component\Monetary\Information\MoneyInformationProvider;
use Maba\Component\Monetary\MoneyCalculator;
use Maba\Component\Monetary\Validation\MoneyValidator;


$math = new Math(new BcMath());
$informationProvider = new MoneyInformationProvider();
$factory = new MoneyFactory($math, new MoneyValidator($math, $informationProvider, new NumberValidator()));
$calculator = new MoneyCalculator($math, $factory, $informationProvider);

$formatter = new MoneyFormatter(
    $calculator,
    $informationProvider,
    new NumberFormatter($math),
    [],
    '%amount%'
);

$exchangeRates = new ExchangeRateCollection();
Util::getOrCreateExchangeRate($exchangeRates, 'EUR', 1);
Util::getOrCreateExchangeRate($exchangeRates, 'USD', 1.1297);
Util::getOrCreateExchangeRate($exchangeRates, 'JPY', 119.51);

//TODO: We can create Factory and load data from different sources
$filePath = $argv[1];
$stream = new CsvReader([
   'filePath' => $filePath
]);

$commission = new Commission([
    'exchangeRates' => $exchangeRates,
    'calculator' => $calculator,
    'formatter' => $formatter
]);
$commission->run($stream);
