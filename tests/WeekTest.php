<?php

namespace Commission\Tests;

use Commission\Model\Date;

class WeekTest extends AbstractTest
{
    /**
     *
     * @param string $date
     * @param string $expected
     *
     * @dataProvider inputProvider
     */
    public function test_input_valid_income($date, $expected)
    {
        $this->shouldBe('date ' . $date, function () use ($date, $expected) {
            $calculated = (new Date(new \DateTimeImmutable($date)))->getWeek();
            verify($calculated)->same($expected);
        });
    }


    public function inputProvider()
    {
        return [
              ['2015-12-31', '201553'],
              ['2016-01-01', '201553'],
              ['2016-01-06', '201601'],
              ['2014-12-31', '201501'],
              ['2015-01-01', '201501'],
              ['2016-02-19', '201607']
        ];
    }
}
