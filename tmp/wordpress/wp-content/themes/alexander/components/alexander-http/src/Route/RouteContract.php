<?php

namespace Macedonia\Http\Route;

/**
 * Interface RouteContract.
 */
interface RouteContract
{
    /**
     * @param string $path
     * @param string $handler
     */
    public static function get(string $path, string $handler): void;

    /**
     * @param string $path
     * @param string $handler
     */
    public static function post(string $path, string $handler): void;

    /**
     * @param string $path
     * @param string $handler
     */
    public static function put(string $path, string $handler): void;

    /**
     * @param string $path
     * @param string $handler
     */
    public static function delete(string $path, string $handler): void;

    /**
     * @param string $path
     * @param string $handler
     */
    public static function options(string $path, string $handler): void;

    /**
     * @param string $path
     * @param string $handler
     */
    public static function head(string $path, string $handler): void;

    /**
     * Registers a REST API route.
     *
     * @return bool
     */
    public static function register(): bool;

    /**
     * @param string $namespace
     */
    public static function setNamespace(string $namespace): void;
}
