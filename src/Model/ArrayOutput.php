<?php

namespace Commission\Model;

use Commission\Base\Model;
use Commission\Model\Interfaces\OutputStreamInterface;
use Maba\Component\Monetary\Formatting\MoneyFormatter;
use Maba\Component\Monetary\MoneyInterface;

/**
 * Array Output
 */
class ArrayOutput extends Model implements OutputStreamInterface
{
    /**
     * @var array
     */
    protected $commission = [];

    /**
     * @return OutputStreamInterface
     */
    public function getStream()
    {
        return $this;
    }

    /**
     * @return OutputStreamInterface
     */
    public function getItems()
    {
        return $this->commission;
    }

    /**
     * @param MoneyInterface $money
     * @param MoneyFormatter $formatter
     */
    public function stream(MoneyInterface $money, MoneyFormatter $formatter)
    {
        $this->commission[] = $formatter->formatMoney($money);
    }

    public function closeStream()
    {
    }
}
