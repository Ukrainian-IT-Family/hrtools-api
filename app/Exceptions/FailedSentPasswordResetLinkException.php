<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;

final class FailedSentPasswordResetLinkException extends BaseException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct(__('passwords.failed_send'), 400, $previous);
    }
}
