<?php
declare(strict_types=1);

namespace WorkDateTime\Provider\Holiday\DTO;

use WorkDateTime\DTO\AbstractDay;

final class Holiday extends AbstractDay
{
    public static function getFromTimestamp(int $timestamp): self
    {
        return new self(
            (int) date('Y', $timestamp),
            (int) date('m', $timestamp),
            (int) date('d', $timestamp)
        );
    }

    public static function getFromDateTime(\DateTime $date_time): self
    {
        return new self(
            (int) date('Y', $date_time->getTimestamp()),
            (int) date('m', $date_time->getTimestamp()),
            (int) date('d', $date_time->getTimestamp())
        );
    }
}
