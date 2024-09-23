<?php

namespace App\Enums;

enum FilterType: string
{
    case LastDay = 'last_day';
    case LastWeek = 'last_week';
    case LastMonth = 'last_month';
    case LastYear = 'last_year';
    case All = 'all';
}
