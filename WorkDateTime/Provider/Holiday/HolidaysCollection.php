<?php declare(strict_types=1);

namespace WorkDateTime\Provider\Holiday;

use WorkDateTime\Provider\Holiday\DTO\Holiday;

class HolidaysCollection
{
    /** @var Holiday[] */
    private array $holidays;

    public function __construct(Holiday ...$holidays)
    {
        $this->holidays = [];
        foreach ($holidays as $holiday) {
            $this->holidays[$holiday->getYear()][$holiday->getMonth()][$holiday->getDay()] = $holiday;
        }
    }

    public function getByTimestamp(int $timestamp): ?Holiday
    {
        [$year, $month, $day] = [
            (int) date('Y', $timestamp),
            (int) date('m', $timestamp),
            (int) date('d', $timestamp)
        ];
        return $this->holidays[$year][$month][$day] ?? null;
    }
}
