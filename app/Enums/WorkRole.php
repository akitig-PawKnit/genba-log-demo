<?php

namespace App\Enums;

enum WorkRole: string
{
    case Regular = 'regular';
    case Support = 'support';
    case Foreman = 'foreman';
}
