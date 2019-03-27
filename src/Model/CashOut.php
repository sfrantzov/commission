<?php

namespace Commission\Model;

use Commission\Base\Model;
use Commission\Collection\CashOutCollection;
use Maba\Component\Monetary\Money;
use Maba\Component\Monetary\MoneyInterface;

/**
 * CashOut object
 *
 */
class CashOut extends Model
{
    /**
     * @var CashOutCollection
     */
    protected $cashOut;

    /**
     * @var array
     */
    protected $count = [];

    /**
     * get cash out
     *
     * @param $week
     * @return MoneyInterface
     */
    public function getCashOut($week)
    {
        $this->initCounter($week);
        if (!isset($this->cashOut[$week])) {
            $this->cashOut[$week] = new Money(0, null);
        }
        return $this->cashOut[$week];
    }

    /**
     * Set cash out
     *
     * @param MoneyInterface $cashOut
     * @param int $week
     */
    public function setCashOut(MoneyInterface $cashOut, $week)
    {
        if (empty($this->cashOut)) {
            $this->cashOut = new CashOutCollection();
        }
        $this->initCounter($week);
        $this->count[$week]++;
        $this->cashOut[$week] = $cashOut;
    }

    /**
     * get cash out
     *
     * @param int $week
     * @return array
     */
    public function getCount($week)
    {
        $this->initCounter($week);
        return $this->count[$week];
    }

    protected function initCounter($week)
    {
        if (!isset($this->cashOut[$week])) {
            $this->count[$week] = 0;
        }
    }
}
