<?php

namespace Commission\Base;

use Commission\Collection\ExchangeRateCollection;
use Commission\Collection\UserCollection;
use Commission\Model\ExchangeRate;
use Commission\Model\User;

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
                'userId' => $userId
            ]);
            $collection[$userId] = $user;
            return $user;
        }
    }

    /**
     * @param \DateTimeImmutable $date
     * @return string
     */
    public static function getWeek(\DateTimeImmutable $date)
    {
        $week = $date->format('YW');
        $weekOnly = (int) $date->format('W');

        if ($date->format('m') === '12' && $weekOnly === 1) {
            return ((int)$date->format('Y') + 1) . '01';
        }
        if ($date->format('m') === '01' && $weekOnly > 51) {
            return ((int)$date->format('Y') - 1) . $date->format('W');
        }

        return $week;
    }
}