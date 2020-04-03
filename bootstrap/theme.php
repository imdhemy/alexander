<?php

/**
 * ------------------------------------------------
 * Register the REST API endpoints
 * ------------------------------------------------
 *
 * The first thing we will do is to register the REST API endpoints.
 */

use Macedonia\Http\Route\Route;

// Set the endpoints namespace
// TODO: get the namespace from configs
Route::setNamespace("alexander");
// TODO: get the namespace from configs
Route::setControllersNamespace("\Alexander\Http\Controllers");

require_once __DIR__ . "/../routes/api.php";
Route::register();
