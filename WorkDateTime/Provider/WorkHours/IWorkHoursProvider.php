<?php declare(strict_types=1);

namespace WorkDateTime\Provider\WorkHours;

use WorkDateTime\Provider\WorkHours\DTO\IWeekDay;

interface IWorkHoursProvider
{

    public function getWorkDays(): WorkDaysCollection;

    public function getWeekDay(int $week_day): IWeekDay;
}
