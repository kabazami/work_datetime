<?php declare(strict_types=1);

namespace WorkDateTime\Provider\WorkHours;

use WorkDateTime\Provider\WorkHours\DTO\WorkDay;

final class WorkDaysCollection
{
    /** @var WorkDay[] */
    private array $holidays;

    public function __construct(WorkDay ...$holidays)
    {
        $this->holidays = [];
        foreach ($holidays as $holiday) {
            $this->holidays[$holiday->getYear()][$holiday->getMonth()][$holiday->getDay()] = $holiday;
        }
    }

    public function getByTimestamp(int $timestamp): ?WorkDay
    {
        [$year, $month, $day] = [
            (int) date('Y', $timestamp),
            (int) date('m', $timestamp),
            (int) date('d', $timestamp)
        ];
        return $this->holidays[$year][$month][$day] ?? null;
    }
}
