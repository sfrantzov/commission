<?php

namespace Commission\Tests;

use Assert\InvalidArgumentException;
use Commission\Model\Input;

class InputTest extends AbstractTest
{
    protected $modelClass = Input::class;

    /**
     *
     * @param string $date
     * @param int $userId
     * @param string $userType
     * @param string $operationType
     * @param float $amount
     * @param string $currency
     *
     * @dataProvider inputProvider
     */
    public function test_input_valid_income($date, $userId, $userType, $operationType, $amount, $currency)
    {
        $this->setConfig();
        $this->shouldBe('input for ' . $date . ' userId ' . $userId . ' type ' . $userType. ' operation ' . $operationType . ' amount ' . $amount . ' currency ' . $currency, function () use ($date, $userId, $userType, $operationType, $amount, $currency) {

            $this->getInput([
                'date' => $date,
                'userId' => $userId,
                'userType' => $userType,
                'operationType' => $operationType,
                'amount' => $amount,
                'currency' => $currency
            ]);
        });
    }

    /**
     *
     * @param string $date
     * @param int $userId
     * @param string $userType
     * @param string $operationType
     * @param float $amount
     * @param string $currency
     *
     * @dataProvider invalidInputProvider
     */
    public function test_input_invalid_income($date, $userId, $userType, $operationType, $amount, $currency)
    {

        $this->setConfig();
        $this->shouldThrowExceptionIf('input for ' . $date . ' userId ' . $userId . ' type ' . $userType. ' operation ' . $operationType . ' amount ' . $amount . ' currency ' . $currency, function () use ($date, $userId, $userType, $operationType, $amount, $currency) {

            $this->getInput([
                'date' => $date,
                'userId' => $userId,
                'userType' => $userType,
                'operationType' => $operationType,
                'amount' => $amount,
                'currency' => $currency
            ]);
        }, [
            'throws' => InvalidArgumentException::class
        ]);
    }

    public function inputProvider()
    {
        return [
              ['2016-01-06', '2',   'legal', 'cash_out',  '300.00', 'EUR'],
              ['2016-01-06', '1', 'natural', 'cash_out',   '30000', 'JPY'],
              ['2016-01-07', '1', 'natural', 'cash_out', '1000.00', 'EUR'],
              ['2016-01-07', '1', 'natural', 'cash_out',  '100.00', 'USD']
        ];
    }

    public function invalidInputProvider()
    {
        return [
            ['2016-01-06', '-2',   'legal', 'cash_out',  '300.00', 'EUR'],
            ['2016-01-06',  '1',  'bastun', 'cash_out',   '30000', 'JPY'],
            ['2016-01-07',  '1', 'natural',      'foo', '1000.00', 'EUR'],
            ['2016-01-07',  '1', 'natural', 'cash_out', '-100.00', 'USD'],
            ['2016-01-07',  '1', 'natural', 'cash_out',  '100.00',    ''],
            ['2016-01-07',   '', 'natural', 'cash_out',  '100.00', 'EUR'],
            ['2016-01-07',  '1',        '', 'cash_out',  '100.00', 'EUR'],
            ['2016-01-07',  '1', 'natural',         '',  '100.00', 'EUR'],
            ['2016-01-07',  '0', 'natural', 'cash_out',  '100.00', 'EUR'],
            ['2016-01-06',  '2',   'legal', 'cash_out',       '0', 'EUR'],
            ['2016-01-06',  '2',   'legal', 'cash_out',     'bau', 'EUR'],
            ['',            '1', 'natural', 'cash_out',  '100.00', 'USD'],
            ['dum',         '1', 'natural', 'cash_out',  '100.00', 'USD']
        ];
    }
}
