<?php

namespace Commission\Tests;

use Assert\InvalidArgumentException;
use Commission\Logic\BaseLogic\CashInLogic;
use Maba\Component\Monetary\Formatting\FormattingContext;
use Maba\Component\Monetary\Money;

class CashInLogicTest extends AbstractTest
{
    protected $logicClass = CashInLogic::class;

    /**
     *
     * @param float $amount
     * @param string $expectedAmount
     * @param string $currency
     *
     * @dataProvider inputProvider
     */
    public function test_cash_in_valid_income($amount, $expectedAmount, $currency)
    {
           $this->shouldBe('cash in commission for ' . $amount . ' ' . $currency . ' is ' . $expectedAmount, function () use ($amount, $expectedAmount, $currency) {

            $input = $this->getInput([
                'date' => 'now',
                'userId' => 1,
                'userType' => 'natural',
                'operationType' => 'cash_in',
                'amount' => $amount,
                'currency' => $currency
            ]);

            $commission =  (new $this->logicClass($this->getLogicConfig()))->process($this->getApplication(), $this->getUser(1), $input);

            $formatter = $this->getApplication()->getFormatter();
            $context = new FormattingContext();
            $context->setTemplate('%amount% %currency%');
            $calculated = $formatter->formatMoney($commission, $context);
            $expected = $expectedAmount . ' ' . $currency;

            verify($calculated)->same($expected);
        });

    }

    /**
     *
     * @param float $amount
     * @param string $expectedAmount
     * @param string $currency
     *
     * @dataProvider invalidInputProvider
     */
    public function test_cash_in_invalid_income($amount, $expectedAmount, $currency)
    {
        $this->shouldThrowExceptionIf('Invalid currency ' . $currency, function () use ($amount, $expectedAmount, $currency) {

            $input = $this->getInput([
                'date' => 'now',
                'userId' => 1,
                'userType' => 'natural',
                'operationType' => 'cash_in',
                'amount' => $amount,
                'currency' => $currency
            ]);

            (new $this->logicClass($this->getLogicConfig()))->process($this->getApplication(), $this->getUser(1), $input);
        }, [
            'throws' => InvalidArgumentException::class
        ]);

    }

    public function inputProvider()
    {
        return [
              [10.00,      '0.03', 'EUR'],
              [163.00,     '0.49', 'EUR'],
              [164.00,     '0.50', 'EUR'],
              [200.00,     '0.50', 'EUR'],
              [1000000.00, '0.50', 'EUR'],
              [10.00,      '0.03', 'USD'],
              [183.00,     '0.55', 'USD'],
              [186.60,     '0.56', 'USD'],
              [186.90,     '0.57', 'USD'],
              [187.00,     '0.57', 'USD'],
              [200.00,     '0.57', 'USD'],
              [1000000.00, '0.57', 'USD'],
              [2000000,      '60', 'JPY'],

        ];
    }

    public function invalidInputProvider()
    {
        return [
            [ 10.00, '0.03', 'BGN'],
            [163.00, '0.49', 'XOF'],
            [164.00, '0.50', 'AED'],
            [164.00, '0.50',    '']
        ];
    }
}
