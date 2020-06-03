<?php

if (!function_exists('env')) {
    function env(string $key, $default = null): ?string
    {
        return getenv($key) ?: $default;
    }
}
