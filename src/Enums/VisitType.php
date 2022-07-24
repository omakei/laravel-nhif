<?php

namespace Omakei\LaravelNHIF\Enums;

enum VisitType: int
{
    case NORMAL_VISIT = 1;
    case EMERGENCY = 2;
    case REFERRAL = 3;
    case FOLLOW_UP_VISIT = 4;
}
