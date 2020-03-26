<?php


namespace Macedonia\Alex;

use Exception;
use ReflectionClass;
use Symfony\Component\Console\Application;

/**
 * Class Console
 * @package Macedonia\Alex
 */
class Console
{
    /**
     * @var Application
     */
    private $console;

    /**
     * @var self
     */
    private static $instance;

    /**
     * Console constructor.
     */
    private function __construct()
    {
        $this->console = new Application();
    }

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (is_null(static::$instance)) {
            static::$instance = (new self())->registerDefaultCommands();
        }
        return static::$instance;
    }

    /**
     * @param array $commands
     * @return Console
     */
    public function addCommands(array $commands): self
    {
        $this->console->addCommands($commands);
        return $this;
    }

    /**
     * @param string $dir
     * @param string $namespace
     * @return $this
     */
    public function addCommandsDir(string $dir, string $namespace): self
    {
        $this->addCommands($this->createCommandObjectsFromDir($dir, $namespace));
        return $this;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function run(): int
    {
        return $this->console->run();
    }

    /**
     * @return $this
     */
    private function registerDefaultCommands(): self
    {
        $dir = __DIR__ . "/Commands";
        $namespace = "\\Macedonia\\Alex\\Commands";
        $this->addCommandsDir($dir, $namespace);
        return $this;
    }

    /**
     * Get class names from the specified directory
     *
     * @param string $dir
     * @return array
     */
    private function getClassNamesFromDirectory(string $dir): array
    {
        $pattern = "{$dir}/*.php";
        $files = glob($pattern);
        return array_map(function ($path) {
            return basename($path, ".php");
        }, $files);
    }

    /**
     * @param string $dir
     * @param string $namespace
     * @return array
     */
    private function createCommandObjectsFromDir(string $dir, string $namespace): array
    {
        $classNames = $this->getClassNamesFromDirectory($dir);
        $commandObjects = array_map(function (string $className) use ($namespace) {
            $class = "{$namespace}\\{$className}";
            $reflectionClass = new ReflectionClass($class);
            if ($reflectionClass->isInstantiable()) {
                return new $class();
            }
            return null;
        }, $classNames);
        return array_filter($commandObjects);
    }
}
