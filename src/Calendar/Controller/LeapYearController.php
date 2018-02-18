<?php

namespace Calendar\Controller;

use Calendar\Model\LeapYear;
use Symfony\Component\HttpFoundation\Request;

class LeapYearController
{
    public function indexAction(Request $request)
    {
        $leapYear = new LeapYear();
        if ($leapYear->isLeapYear($request->attributes->get('year'))) {
            return 'Yep, this is a leap year.';
        }

        return 'Nope, this is not a leap year.';
    }
}