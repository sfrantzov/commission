<?php

namespace Commission\Base;

/**
 *  Date
 */
class Date
{
    /** @var  \DateTimeImmutable */
    protected $date;

    /**
     * @param \DateTimeImmutable $date
     */
    public function __construct(\DateTimeImmutable $date)
    {
        $this->date = $date;
    }


    /**
     * @return string
     */
    public function getWeek()
    {
        $week = $this->date->format('YW');
        $weekOnly = (int) $this->date->format('W');

        if ($this->date->format('m') === '12' && $weekOnly === 1) {
            return ((int)$this->date->format('Y') + 1) . '01';
        }
        if ($this->date->format('m') === '01' && $weekOnly > 51) {
            return ((int)$this->date->format('Y') - 1) . $this->date->format('W');
        }

        return $week;
    }
}