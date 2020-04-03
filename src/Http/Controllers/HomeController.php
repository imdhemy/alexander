<?php


namespace Alexander\Http\Controllers;


class HomeController
{
    public function index()
    {
        $message = "Welcome, to Alexander!";
        return compact('message');
    }
}
