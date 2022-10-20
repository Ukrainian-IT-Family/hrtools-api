<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;

final class TooManyVacationDaysArePendingThanAvailableException extends BaseException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct(__('exceptions.too_many_vacation_days_are_pending_than_available'), 400, $previous);
    }
}
