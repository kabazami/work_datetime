<?php declare(strict_types=1);

namespace WorkDateTime\Provider\Holiday;

interface IHolidaysProvider
{

    public function getHolidays(): HolidaysCollection;

    /**
     * @return int[]
     */
    public function getWeekendDays(): array;
}
