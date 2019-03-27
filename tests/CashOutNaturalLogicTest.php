<?php

namespace Commission\Tests;

use Assert\InvalidArgumentException;
use Commission\Base\Config;
use Commission\Collection\UserCollection;
use Commission\Commission;
use Commission\Logic\BaseLogic\CashOutNaturalLogic;
use Maba\Component\Monetary\Formatting\FormattingContext;

class CashOutNaturalLogicTest extends AbstractTest
{
    protected $logicClass = CashOutNaturalLogic::class;

    protected static $users;

    public static function setUpBeforeClass()
    {
        $configArray = (array) include 'tests.config.php';
        $config = new Config();
        $config->setConfig($configArray);
        $application = new Commission($config);
        self::$users = new UserCollection();
        $application->getOrCreateUser(self::$users, 1);
        $application->getOrCreateUser(self::$users, 4);
    }

    /**
     * @param string $date
     * @param int $userId
     * @param float $amount
     * @param string $expectedAmount
     * @param string $currency
     *
     * @dataProvider inputProvider
     */
    public function test_cash_out_valid_income($date, $userId, $amount, $expectedAmount, $currency)
    {
        $this->shouldBe('cash out  commission for date ' . $date . ' user ' . $userId . ' amount ' . $amount . ' ' . $currency . ' is ' . $expectedAmount, function () use ($date, $userId, $amount, $expectedAmount, $currency) {

            $input = $this->getInput([
                'date' => $date,
                'userId' => $userId,
                'userType' => 'natural',
                'operationType' => 'cash_out',
                'amount' => $amount,
                'currency' => $currency
            ]);

            $application = $this->getApplication();

            $users = self::$users;
            $user = $application->getOrCreateUser($users, $input->getUserId());
            $user->setUserType($input->getUserType());

            $commission =  (new $this->logicClass($this->getLogicConfig()))->process($application, $user, $input);

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
        $this->shouldThrowExceptionIf('Invalid currency ' . $currency, function () use ($amount, $expectedAmount, $currency) {
            $input = $this->getInput([
                'date' => 'now',
                'userId' => 1,
                'userType' => 'legal',
                'operationType' => 'cash_out',
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
            ['2016-01-06', 1,   30000,    '0', 'JPY'],
            ['2016-01-07', 1, 1000.00, '0.76', 'EUR'],
            ['2016-01-07', 1,  100.00, '0.30', 'USD'],
            ['2016-01-10', 1,  100.00, '0.30', 'EUR'],
            ['2016-02-15', 1,  300.00, '0.00', 'EUR'],
            ['2014-12-31', 4, 1200.00, '0.60', 'EUR'],
            ['2015-01-01', 4, 1000.00, '3.00', 'EUR'],
            ['2016-01-05', 4,  200.00, '0.00', 'EUR'],
            ['2016-01-05', 4,  200.00, '0.00', 'EUR'],
            ['2016-01-05', 4,  600.01, '0.01', 'EUR'],
            ['2016-01-16', 4, 1000.01, '0.01', 'EUR'],
        ];
    }

    public function invalidInputProvider()
    {
        return [
            [10.00,  '0.03', 'BGN'],
            [163.00, '0.49', 'XOF'],
            [164.00, '0.50', 'AED'],
            [164.00, '0.50', 'KYP']
        ];
    }
}
