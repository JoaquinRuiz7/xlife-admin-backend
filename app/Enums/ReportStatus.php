<?php

namespace App\Enums;

enum ReportStatus: string
{
    case PENDING = 'pending';
    case IN_REVIEW = 'in_review';
    case RESOLVED = 'resolved';
    case DISMISSED = 'dismissed';

}
