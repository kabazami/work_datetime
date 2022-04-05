<?php declare(strict_types=1);

namespace WorkDateTime\Provider\WorkHours\DTO;

interface IWeekDay
{

    public function getStartHour(): int;

    public function getEndHour(): int;
}
