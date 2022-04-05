<?php
declare(strict_types=1);

namespace WorkDateTime\DTO;

abstract class AbstractDay
{

    private int $year;
    private int $month;
    private int $day;

    public function __construct(
        int $year,
        int $month,
        int $day
    )
    {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @return int
     */
    public function getMonth(): int
    {
        return $this->month;
    }

    /**
     * @return int
     */
    public function getDay(): int
    {
        return $this->day;
    }
}
