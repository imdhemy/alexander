<?php

namespace Alexander\Http\Controllers;

class HomeController
{
    public static function index()
    {
        $message = 'Welcome, to Alexander!';

        return compact('message');
    }
}
