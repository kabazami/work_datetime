<?php declare(strict_types=1);

namespace WorkDateTime\Provider\WorkHours\DTO;

final class CommonWeekDay extends AbstractWeekDay
{
    protected int $start_hour = 9;
    protected int $end_hour = 18;
}
