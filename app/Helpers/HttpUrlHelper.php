<?php

namespace App\Helpers;

final class HttpUrlHelper
{
    public static function removeDuplicates(string $url): string
    {
        return preg_replace(
            '/([^:])(\/{2,})/',
            '$1/',
            $url
        );
    }
}
