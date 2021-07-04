<?php

namespace ScienceProject\Date;

class Date {

    const DAYS_IN_MONTH = [
        1 => 31,
        2 => 28,
        3 => 31,
        4 => 30,
        5 => 31,
        6 => 30,
        7 => 31,
        8 => 31,
        9 => 30,
        10 => 31,
        11 => 30,
        12 => 31,
    ];

    const FORMAT_IDENTIFIERS = [
        'd' => [
            'regex' => '\d{1,2}',
            'type' => 'day',
        ],
        'm' => [
            'regex' => '\d{1,2}',
            'type' => 'month',
        ],
        'Y' => [
            'regex' => '\d+',
            'type' => 'year',
        ],
    ];

    protected int $day;
    protected int $month;
    protected int $year;

    private function __construct(int $day, int $month, int $year) {
        if ($month < 1 || $month > 12) {
            throw new InvalidDateException("month '$month' must be between 1 and 12");
        }
        if ($day < 1 || $day > self::DAYS_IN_MONTH[$month] && (!($month === 2 && $day === 29 && self::isLeapYear($year)))) {
            throw new InvalidDateException("day '$day' is not valid for month '$month' in year '$year'");
        }
        $this->day = $day;
        $this->month = $month;
        $this->year = $year;
    }

    public static function create(int $day, int $month, int $year): self {
        return new self($day, $month, $year);
    }

    public static function createFromFormat(string $date, string $format): self {
        $len = strlen($format);
        $formatIdentifierPositions = [];
        $regex = preg_quote($format);
        $y = 1;
        for ($i = 0; $i < $len; $i++) {
            if (isset(self::FORMAT_IDENTIFIERS[$format[$i]])) {
                $formatIdentifierPositions[self::FORMAT_IDENTIFIERS[$format[$i]]['type']] = ['format' => $i, 'regex' => $y++];
                $regex = preg_replace('#'.$format[$i].'#', '(' . self::FORMAT_IDENTIFIERS[$format[$i]]['regex'] . ')', $regex);
            }
        }
        if (!preg_match("#{$regex}#", $date, $formatIdentifierMatches)) {
            throw new InvalidDateException("Invalid date '{$date}', does not match format '{$format}'");
        }
        $requiredComponents = ['day', 'month', 'year'];
        foreach ($requiredComponents as $component) {
            if (!isset($formatIdentifierPositions[$component])) {
                throw new InvalidDateException("Missing $component component in format '$format'");
            }
        }
        $day = (int)$formatIdentifierMatches[$formatIdentifierPositions['day']['regex']];
        $month = (int)$formatIdentifierMatches[$formatIdentifierPositions['month']['regex']];
        $year = (int)$formatIdentifierMatches[$formatIdentifierPositions['year']['regex']];
        return self::create($day, $month, $year);
    }

    public static function isLeapYear(int $year): bool {
        return $year % 4 === 0 && ($year % 100 !== 0 || $year % 400 === 0);
    }

    public static function leapDaysSinceJesus(int $year): int {
        return (int)floor($year / 4) - (int)floor($year / 100) + (int)floor($year / 400);
    }

    /**
     * Returns the difference between this date and another date in days (result can be negative)
     */
    public function diffDays(Date $date): int {
        $days = $date->daysElapsedInYear() - $this->daysElapsedInYear();
        $days += $this->diffYearsInDays($date);
        return $days;
    }

    /**
     * Return days elapsed in year
     *
     * Example:
     *
     * If $this is 03/03/2000, function will return 62 (03/03/2000 - 01/01/2000)
     */
    private function daysElapsedInYear(): int {
        $monthsDiff = array_sum(array_slice(self::DAYS_IN_MONTH, 0, $this->month - 1, true));
        $daysDiff = $this->day - 1;
        $days = $monthsDiff + $daysDiff;
        if (self::isLeapYear($this->year) && $this->month > 2) {
            $days++;
        }
        return $days;
    }

    /**
     * Number of days between years of $this and $date assuming day 1 of year, e.g. 01/01/$year - 01/01/$year
     *
     * Result can be negative
     */
    private function diffYearsInDays(Date $date): int {
        $diff = $date->year - $this->year;
        $days = 365 * $diff;
        // + leap days
        $days += self::leapDaysSinceJesus($date->year) - self::leapDaysSinceJesus($this->year);
        return $days;
    }

    public function year(): int {
        return $this->year;
    }
}