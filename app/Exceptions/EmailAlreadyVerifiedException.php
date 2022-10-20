<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;

final class EmailAlreadyVerifiedException extends BaseException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct(__('register.email_already_verified'), 400, $previous);
    }
}
