<?php
declare(strict_types=1);

namespace WorkDateTime\Provider\WorkHours;

use WorkDateTime\Dictionary\WeekDay;
use WorkDateTime\Provider\WorkHours\DTO\CommonWeekDay;
use WorkDateTime\Provider\WorkHours\DTO\IWeekDay;

final class WeekDayFactory
{
    public static function get(int $week_day): IWeekDay
    {
        switch ($week_day) {
            case WeekDay::COMMON:
            default:
                return new CommonWeekDay();
        }
    }
}
