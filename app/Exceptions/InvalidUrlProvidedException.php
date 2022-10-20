<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;

final class InvalidUrlProvidedException extends BaseException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct(__('register.invalid_url_provided'), 401, $previous);
    }
}
