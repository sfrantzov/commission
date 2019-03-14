<?php

namespace Commission;

use Commission\Base\Util;
use Commission\Collection\UserCollection;
use Commission\Model\ExchangeRate;
use Commission\Model\Input;
use Commission\Model\Interfaces\StreamInterface;
use Maba\Component\Monetary\Formatting\MoneyFormatter;
use Maba\Component\Monetary\Money;
use Maba\Component\Monetary\MoneyCalculator;

/**
 *
 */
class Commission
{
    /**
     * @var MoneyCalculator
     */
    private $calculator;

    /**
     * @var ExchangeRate
     */
    private $exchangeRates;

    /**
     * @var MoneyFormatter
     */
    private $formatter;

    const DEFAULT_CURRENCY = 'EUR';

    public function __construct($params)
    {
        $this->calculator = $params['calculator'];
        $this->exchangeRates = $params['exchangeRates'];
        $this->formatter = $params['formatter'];
    }

    public function run(StreamInterface $stream)
    {
        $users = new UserCollection();
        $stream->getStream();

        /* @var Input $input */
        while ($input = $stream->getRow()) {
            $user = Util::getOrCreateUser($users, $input->userId);

            $originalAmount = new Money($input->amount, $input->currency);
            $convertedAmount = $this->convertAmount($originalAmount);

            if ($input->operationType == Input::CASH_IN) {
                $cashIn = $this->calculator->ceil(
                    $this->calculator->add(
                        $convertedAmount,
                        $user->cashIn
                    )
                );
                $user->cashIn = $cashIn;
            } else {
                $cashOut = $this->calculator->ceil(
                    $this->calculator->add(
                        $convertedAmount,
                        $user->cashOut
                    )
                );
                $user->cashOut = $cashOut;
            }
        }
        dump($users);
    }

    public function convertAmount(Money $originalAmount)
    {
        $convertedAmount = $this->calculator->ceil(
            $this->calculator->div(
                new Money($originalAmount->getAmount(), self::DEFAULT_CURRENCY),
                $this->exchangeRates[$originalAmount->getCurrency()]->getExchangeRate()
            )
        );
        return $convertedAmount;
    }
}
