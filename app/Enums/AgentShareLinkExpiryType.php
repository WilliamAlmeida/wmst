<?php

namespace App\Enums;

enum AgentShareLinkExpiryType: string
{
    case FixedDatetime = 'fixed_datetime';
    case RelativeDuration = 'relative_duration';
}
