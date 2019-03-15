<?php

namespace Commission\Model;

use Commission\Base\Model;
use Commission\Model\Interfaces\OutputStreamInterface;
use Maba\Component\Monetary\Formatting\MoneyFormatter;
use Maba\Component\Monetary\MoneyInterface;

/**
 * Console Output
 */
class ConsoleOutput extends Model implements OutputStreamInterface
{

    /**
     * @return OutputStreamInterface
     */
    public function getStream()
    {
        return $this;
    }

    /**
     * @param MoneyInterface $money
     * @param MoneyFormatter $formatter
     */
    public function stream(MoneyInterface $money, MoneyFormatter $formatter)
    {
        echo $formatter->formatMoney($money) . PHP_EOL;
    }

    public function closeStream()
    {
    }
}
