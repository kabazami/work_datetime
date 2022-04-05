<?php
declare(strict_types=1);

namespace WorkDateTime\Provider\WorkHours\DTO;

use WorkDateTime\Dictionary\WeekDay;

abstract class AbstractWeekDay implements IWeekDay
{
    protected int $start_hour = 0;
    protected int $end_hour = 23;
    protected int $week_day = WeekDay::COMMON;

    public function getStartHour(): int
    {
        return $this->start_hour;
    }

    public function getEndHour(): int
    {
        return $this->end_hour;
    }

    /**
     * @return int
     */
    public function getWeekDay(): int
    {
        return $this->week_day;
    }
}
