<?php
declare(strict_types=1);

namespace WorkDateTime;

use WorkDateTime\Provider\Holiday\DefaultHolidaysProvider;
use WorkDateTime\Provider\Holiday\DTO\Holiday;
use WorkDateTime\Provider\Holiday\IHolidaysProvider;
use WorkDateTime\Provider\WorkHours\DefaultWorkHoursProvider;
use WorkDateTime\Provider\WorkHours\DTO\WorkDay;
use WorkDateTime\Provider\WorkHours\IWorkHoursProvider;

class WorkDateTime extends \DateTime
{
    private const DAY_INTERVAL = 'P1D';
    private const HOUR_INTERVAL = 'PT1H';
    private const LAST_HOUR = 23;
    private const LAST_MINUTE = 59;
    private const LAST_SECOND = 59;

    private int $week_day;
    private ?IHolidaysProvider $holidays_provider = null;
    private ?IWorkHoursProvider $work_hours_provider = null;

    public function __construct(
        string $datetime = "now",
        \DateTimeZone $timezone = null
    )
    {
        parent::__construct($datetime, $timezone);
        $this->recalculateWeekDay();
    }

    public function addWorkDays(int $days): self {
        $this->skipToClosestWorkDay();
        for ($days_to_add = 0; $days_to_add < $days; $days_to_add++) {
            $this->addWorkDay(true);
        }
        // TODO - Think about preserving original hours and minutes
        $this->setTime(
            $this->getStartHour(),
            0
        );
        return $this;
    }

    public function addWorkHours(int $hours): self {
        $this->skipToClosestWorkDay(true);
        $added_hours = 0;
        do {
            $this->add((new \DateInterval(self::HOUR_INTERVAL)))->recalculateWeekDay();
            $added_hours++;
            $this->skipToClosestWorkDay(true);
        } while ($added_hours < $hours);
        return $this;
    }

    public function isWeekend(): bool {
        return in_array(
            $this->getWeekDay(),
            $this->getHolidaysProvider()->getWeekendDays(),
            true
        );
    }

    public function isHoliday(): bool {
        return $this->getHolidaysProvider()
            ->getHolidays()->getByTimestamp($this->getTimestamp()) instanceof Holiday;
    }

    public function isDayOff(): bool {
        return $this->isWeekend()
            || $this->isHoliday();
    }

    public function setWorkHoursProvider(IWorkHoursProvider $work_hours_provider): self
    {
        $this->work_hours_provider = $work_hours_provider;
        return $this;
    }

    private function getWorkHoursProvider(): IWorkHoursProvider
    {
        if ($this->work_hours_provider instanceof IWorkHoursProvider) {
            return $this->work_hours_provider;
        }
        return $this->work_hours_provider = new DefaultWorkHoursProvider();
    }

    public function setHolidaysProvider(IHolidaysProvider $holidays_provider): self
    {
        $this->holidays_provider = $holidays_provider;
        return $this;
    }

    private function getHolidaysProvider(): IHolidaysProvider
    {
        if ($this->holidays_provider instanceof IHolidaysProvider) {
            return $this->holidays_provider;
        }
        return $this->holidays_provider = new DefaultHolidaysProvider();
    }

    /**
     * @return int
     */
    public function getWeekDay(): int
    {
        return $this->week_day;
    }

    private function recalculateWeekDay(): void
    {
        $this->week_day = idate('w', $this->getTimestamp());
    }

    private function addWorkDay(bool $keep_minutes = false): void
    {
        $minutes = 0;
        if ($keep_minutes) {
            $minutes = (int)date('i', $this->getTimestamp());
        }
        do {
            $this->add((new \DateInterval(self::DAY_INTERVAL)))
                ->setTime(
                    $this->getStartHour(),
                    $minutes
                )
                ->recalculateWeekDay();
        } while ($this->isDayOff());
    }

    private function skipToClosestWorkDay(bool $keep_minutes = false): self {
        if (
            !$this->isNotInWorkHours()
            && !$this->isDayOff()
        ) {
            return $this;
        }
        $minutes = 0;
        if ($keep_minutes) {
            $minutes = (int)date('i', $this->getTimestamp());
        }
        if (
            $this->isAfterWorkHours()
            || $this->isDayOff()
        ) {
            do {
                $this->add((new \DateInterval(self::DAY_INTERVAL)))->recalculateWeekDay();
            } while ($this->isDayOff());
            return $this->setTime(
                $this->getStartHour(),
                $minutes
            );
        }
        if ($this->isBeforeWorkHours()) {
            return $this->setTime(
                $this->getStartHour(),
                $minutes
            );
        }
        return $this;
    }

    private function isNotInWorkHours(): bool
    {
        return $this->isBeforeWorkHours()
            || $this->isAfterWorkHours();
    }

    private function isBeforeWorkHours(): bool
    {
        return date('G', $this->getTimestamp()) < $this->getStartHour();
    }

    private function isAfterWorkHours(): bool
    {
        $end_min = 0;
        $end_sec = 0;
        if ($this->getEndHour() === self::LAST_HOUR) {
            $end_min = self::LAST_MINUTE;
            $end_sec = self::LAST_SECOND;
        }
        return date('G', $this->getTimestamp()) >= $this->getEndHour()
            && date('i', $this->getTimestamp()) >= $end_min
            && date('s', $this->getTimestamp()) >= $end_sec;
    }

    private function getStartHour(): int
    {
        $work_day = $this->getWorkHoursProvider()->getWorkDays()->getByTimestamp($this->getTimestamp());
        if ($work_day instanceof WorkDay) {
            return $work_day->getStartHour();
        }
        return $this->getWorkHoursProvider()->getWeekDay($this->getWeekDay())->getStartHour();
    }

    private function getEndHour(): int
    {
        $work_day = $this->getWorkHoursProvider()->getWorkDays()->getByTimestamp($this->getTimestamp());
        if ($work_day instanceof WorkDay) {
            return $work_day->getEndHour();
        }
        return $this->getWorkHoursProvider()->getWeekDay($this->getWeekDay())->getEndHour();
    }
}
