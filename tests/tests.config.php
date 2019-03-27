<?php
/**
* test config
*/
return
[
    'application' => [
        /**
         * default currency
         */
        'defaultCurrency' => 'EUR',

        'currencyExchangeRates' => [
            'EUR' => 1,
            'USD' => 1.1297,
            'JPY' => 119.51
        ]
    ],
    'baseLogic' => [
        /**
         * cash in commission in %
         */
        'cashInCommission' => 0.3,
        /**
         * max cash in commission in default currency
         */
        'maxCashInCommission' => 0.5,
        /**
         * legal cash out commission in %
         */
        'legalCashOutCommission' => 3,
        /**
         * min legal cash out commission in default currency
         */
        'minLegalCashOutCommission' => 5,
        /**
         * natural cash out commission in %
         */
        'naturalCashOutCommission' => 0.3,
        /**
         * free cash outs not more than maxNaturalCashOutCommission amount
         */
        'maxNaturalFreeCashOut' => 3,
        /**
         * max legal cash out amount for free cash out in default currency for first maxNaturalFreeCashOut cash outs
         */
        'maxNaturalCashOutCommission' => 1000
    ]
];
