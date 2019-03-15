<?php

namespace Commission\Model;

use Commission\Base\Model;

/**
 * Exchange rate object
 */
class ExchangeRate extends Model
{
    /**
     * @var string
     */
    protected $currency;

    /**
     * @var float
     */
    protected $exchangeRate;

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set currency
     *
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Get exchange rate
     *
     * @return float
     */
    public function getExchangeRate()
    {
        return $this->exchangeRate;
    }

    /**
     * Set exchange rate
     *
     * @param float $exchangeRate
     */
    public function setExchangeRate($exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;
    }
}
