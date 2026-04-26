<?php

namespace App\Enums;

enum AgentShareLinkUsageType: string
{
    case SingleUse = 'single_use';
    case MultiUse = 'multi_use';
}
