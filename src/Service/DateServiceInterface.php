<?php

namespace App\Service;

use DateTime;

interface DateServiceInterface
{
    public function getActualTime(): DateTime;
}