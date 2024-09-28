<?php

namespace App\Service;

use DateTime;
use Symfony\Component\HttpFoundation\RequestStack;

class DateService implements DateServiceInterface
{
    public function __construct()
    {
    }

    public function getActualTime(): DateTime
    {
        try
        {
            $parisTimezone = new \DateTimeZone('Europe/Paris');
            $date = new DateTime('now', $parisTimezone);
        } catch (\Exception $e)
        {
            $date = new DateTime();
        }
        return $date;
    }
}