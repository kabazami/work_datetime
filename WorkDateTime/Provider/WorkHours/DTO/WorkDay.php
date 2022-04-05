<?php
declare(strict_types=1);

namespace WorkDateTime\Provider\WorkHours\DTO;

use WorkDateTime\DTO\AbstractDay;

final class WorkDay extends AbstractDay implements IWeekDay
{
    private int $start_hour;
    private int $end_hour;

    public function __construct(
        int $year,
        int $month,
        int $day,
        int $start_hour,
        int $end_hour
    )
    {
        parent::__construct($year, $month, $day);
        $this->start_hour = $start_hour;
        $this->end_hour = $end_hour;
    }

    public static function getFromDateTime(\DateTime $date_time, int $start_hour, int $end_hour): self
    {
        return new self(
            (int) date('Y', $date_time->getTimestamp()),
            (int) date('m', $date_time->getTimestamp()),
            (int) date('d', $date_time->getTimestamp()),
            $start_hour,
            $end_hour
        );
    }
    public static function getFromTimestamp(int $timestamp, int $start_hour, int $end_hour): self
    {
        return new self(
            (int) date('Y', $timestamp),
            (int) date('m', $timestamp),
            (int) date('d', $timestamp),
            $start_hour,
            $end_hour
        );
    }

    public function getStartHour(): int
    {
        return $this->start_hour;
    }

    public function getEndHour(): int
    {
        return $this->end_hour;
    }
}
