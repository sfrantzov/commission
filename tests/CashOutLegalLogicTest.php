<?php

namespace Commission\Tests;

use Assert\InvalidArgumentException;
use Commission\Logic\BaseLogic\CashOutLegalLogic;
use Maba\Component\Monetary\Formatting\FormattingContext;

class CashOutLegalLogicTest extends AbstractTest
{
    protected $logicClass = CashOutLegalLogic::class;

    /**
     *
     * @param float $amount
     * @param string $expectedAmount
     * @param string $currency
     *
     * @dataProvider inputProvider
     */
    public function test_cash_out_valid_income($amount, $expectedAmount, $currency)
    {
        $this->setConfig();
        $this->shouldBe('cash out legal commission for ' . $amount . ' ' . $currency . ' is ' . $expectedAmount, function () use ($amount, $expectedAmount, $currency) {

            $input = $this->getInput([
                'date' => 'now',
                'userId' => 1,
                'userType' => 'legal',
                'operationType' => 'cash_out',
                'amount' => $amount,
                'currency' => $currency
            ]);

            $commission = (new $this->logicClass)->process($this->getApplication(), $this->getUser(1), $input);

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
    public function test_cash_out_invalid_income($amount, $expectedAmount, $currency)
    {
        $this->setConfig();
        $this->shouldThrowExceptionIf('Invalid currency ' . $currency, function () use ($amount, $expectedAmount, $currency) {

            $input = $this->getInput([
                'date' => 'now',
                'userId' => 1,
                'userType' => 'legal',
                'operationType' => 'cash_out',
                'amount' => $amount,
                'currency' => $currency
            ]);

            (new $this->logicClass)->process($this->getApplication(), $this->getUser(1), $input);
        }, [
            'throws' => InvalidArgumentException::class
        ]);

    }

    public function inputProvider()
    {
        return [
            [300.00,   '9.00', 'EUR'],
            [4.00,     '5.00', 'EUR'],
            [5.00,     '5.00', 'EUR'],
            [166.66,   '5.00', 'EUR'],
            [166.67,   '5.01', 'EUR'],
            [167.00,   '5.01', 'EUR'],
            [300.00,   '9.00', 'USD'],
            [4.00,     '5.65', 'USD'],
            [188.33,   '5.65', 'USD'],
            [188.34,   '5.66', 'USD'],
            [3000000, '90000', 'JPY'],
        ];
    }

    public function invalidInputProvider()
    {
        return [
            [10.00,  '0.03', 'BGN'],
            [163.00, '0.49', 'XOF'],
            [164.00, '0.50', 'AED'],
            [164.00, '0.50',    '']
        ];
    }
}
