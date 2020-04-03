<?php

namespace Macedonia\Http\Route;

/**
 * Class Route
 * @package Alexander\Http\Route
 */
class Route implements RouteContract
{

    /**
     * Method names
     */
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    const OPTIONS = 'OPTIONS';
    const HEAD = 'HEAD';

    /**
     * @var string
     */
    private static $namespace = "alexander";

    /**
     * @var string
     */
    private static $controllersNamespace = "\Alexander\Http\Controllers";

    /**
     * @var array
     */
    private static $endPoints = [];

    /**
     * @param string $path
     * @param string $handler
     */
    public static function get(string $path, string $handler): void
    {
        $callback = static::extractCallback($handler);
        static::addEndPoint($path, $callback, self::GET);
    }

    /**
     * @param string $path
     * @param string $handler
     */
    public static function post(string $path, string $handler): void
    {
        $callback = static::extractCallback($handler);
        static::addEndPoint($path, $callback, self::POST);
    }

    /**
     * @param string $path
     * @param string $handler
     */
    public static function put(string $path, string $handler): void
    {
        $callback = static::extractCallback($handler);
        static::addEndPoint($path, $callback, self::PUT);
    }

    /**
     * @param string $path
     * @param string $handler
     */
    public static function delete(string $path, string $handler): void
    {
        $callback = static::extractCallback($handler);
        static::addEndPoint($path, $callback, self::DELETE);
    }

    /**
     * @param string $path
     * @param string $handler
     */
    public static function options(string $path, string $handler): void
    {
        $callback = static::extractCallback($handler);
        static::addEndPoint($path, $callback, self::OPTIONS);
    }

    /**
     * @param string $path
     * @param string $handler
     */
    public static function head(string $path, string $handler): void
    {
        $callback = static::extractCallback($handler);
        static::addEndPoint($path, $callback, self::HEAD);
    }

    /**
     * Registers a REST API route.
     *
     * @return bool True on success, false on error.
     */
    public static function register(): bool
    {
        $endPoints = static::$endPoints;
        add_action('rest_api_init', function () use ($endPoints) {
            foreach ($endPoints as $endPoint) {
                $route = $endPoint['route'];
                $args = $endPoint['args'];
                $namespace = $args['namespace'];
                register_rest_route($namespace, $route, $args);
            }
        });

        return true;
    }

    /**
     * @param string $namespace
     */
    public static function setNamespace(string $namespace): void
    {
        static::$namespace = $namespace;
    }

    /**
     * Extracts callback elements
     *
     * @param string $handler
     *
     * @return array['class', 'method']
     */
    private static function extractCallback(string $handler): array
    {
        $handlerParts = explode("@", $handler, 2);
        $class = $handlerParts[0];
        $class = static::controllerHasStandardNamespace($class) ?
            sprintf("%s\\%s", static::$controllersNamespace, $class) : $class;
        $method = $handlerParts[1];

        return compact('class', 'method');
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    private static function controllerHasStandardNamespace(string $className): bool
    {
        $namespace = str_replace($className, $className, "");
        if (empty($namespace)) {
            return true;
        }
        if ($namespace === static::$controllersNamespace) {
            return true;
        }

        return false;
    }

    /**
     * @param string $route
     * @param array['class', 'method']  $callback
     * @param string $httpMethod
     */
    private static function addEndPoint(string $route, array $callback, string $httpMethod): void
    {
        $class = $callback['class'];
        $method = $callback['method'];
        $args = [
            'namespace' => static::$namespace,
            'methods' => $httpMethod,
            'callback' => [$class, $method]
        ];
        static::$endPoints[] = compact('route', 'args');
    }

    /**
     * @return array
     */
    public static function getEndPointsArray(): array
    {
        return static::$endPoints;
    }

    /**
     * @param string $controllersNamespace
     */
    public static function setControllersNamespace(string $controllersNamespace): void
    {
        self::$controllersNamespace = $controllersNamespace;
    }
}
