<?php declare(strict_types=1);

namespace WorkDateTime\Provider\Holiday;

use WorkDateTime\Dictionary\WeekDay;
use WorkDateTime\Provider\Holiday\DTO\Holiday;

final class DefaultHolidaysProvider implements IHolidaysProvider
{

    public function getHolidays(): HolidaysCollection
    {
        return new HolidaysCollection(
            Holiday::getFromDateTime(\DateTime::createFromFormat('Y-m-d', '2022-04-05'))
        );
    }

    public function getWeekendDays(): array
    {
        return [
            WeekDay::SATURDAY,
            WeekDay::SUNDAY,
        ];
    }
}
