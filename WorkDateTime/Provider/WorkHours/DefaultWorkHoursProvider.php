<?php
declare(strict_types=1);

namespace WorkDateTime\Provider\WorkHours;

use WorkDateTime\Provider\WorkHours\DTO\IWeekDay;
use WorkDateTime\Provider\WorkHours\DTO\WorkDay;

final class DefaultWorkHoursProvider implements IWorkHoursProvider
{

    public function getWorkDays(): WorkDaysCollection
    {
        return new WorkDaysCollection(
            WorkDay::getFromDateTime(\DateTime::createFromFormat('Y-m-d', '2022-04-06'), 10,12)
        );
    }

    public function getWeekDay(int $week_day): IWeekDay
    {
        return WeekDayFactory::get($week_day);
    }
}
