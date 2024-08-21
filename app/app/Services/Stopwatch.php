<?php

namespace App\Services;

/**
 * @see https://replit.com/@w3lifer/php-Stopwatch#main.php
 */
class Stopwatch
{
    private int $startTime;

    public function __construct()
    {
        $this->startTime = hrtime(true);
    }

    public function getSeconds(): int
    {
        return round($this->getSecondsWithNanoseconds());
    }

    public function getSecondsWithMilliseconds(): float
    {
        return round($this->getSecondsWithNanoseconds(), 3);
    }

    public function getSecondsWithMicroseconds(): float
    {
        return round($this->getSecondsWithNanoseconds(), 6);
    }

    public function getSecondsWithNanoseconds(): float
    {
        return (hrtime(true) - $this->startTime) / 10**9;
    }
}
