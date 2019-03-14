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
    private $currency;

    /**
     * @var float
     */
    private $exchangeRate;

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
    public function setCurrency($currecny)
    {
        $this->currency = $currecny;
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
     * @param float
     */
    public function setExchangeRate($exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;
    }
}
