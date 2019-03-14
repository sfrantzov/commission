<?php

namespace Commission\Base;

use Commission\Collection\ExchangeRateCollection;
use Commission\Collection\UserCollection;
use Commission\Model\ExchangeRate;
use Commission\Model\User;
use Maba\Component\Monetary\Money;

/**
 *  Utils to help creating objects
 */
class Util
{

    /**
     * @param ExchangeRateCollection $collection
     * @param string $currency
     * @param float $rate
     * @return ExchangeRate
     */
    public static function getOrCreateExchangeRate(ExchangeRateCollection $collection, $currency, $rate)
    {
        if (isset($collection[$currency])) {
            return $collection[$currency];
        } else {
            $exchangeRate = new ExchangeRate([
                'currency' => $currency,
                'exchangeRate' => $rate
            ]);
            $collection[$currency] = $exchangeRate;
            return $exchangeRate;
        }
    }

    /**
     * @param UserCollection $collection
     * @param int $userId
     * @return User
     */
    public static function getOrCreateUser(UserCollection $collection, $userId)
    {
        if (isset($collection[$userId])) {
            return $collection[$userId];
        } else {
            $user = new User([
                'userId' => $userId,
                'cashIn' => new Money(0, null),
                'cashOut' => new Money(0, null)
            ]);
            $collection[$userId] = $user;
            return $user;
        }
    }
}