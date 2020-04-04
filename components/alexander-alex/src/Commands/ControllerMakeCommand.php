<?php


namespace Macedonia\Alex\Commands;


use Macedonia\Alex\Console;
use Macedonia\Alex\GeneratorCommand;

/**
 * Class ControllerMakeCommand
 * @package Macedonia\Alex\Commands
 */
class ControllerMakeCommand extends GeneratorCommand
{
    /**
     * @var string The Console command signature
     */
    protected $signature = "make:controller";

    /**
     * @var string The console command description
     */
    protected $description = "Creates a new controller class.";

    /**
     * @var string The console command help message
     */
    protected $help = "Creates a new class controller with the specified <name> argument.";

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace(string $rootNamespace): string
    {
        return $rootNamespace . "\\Http\\Controllers";
    }

    /**
     * Get the root namespace of the class
     * @return string
     */
    protected function getRootNamespace(): string
    {
        return "Alexander";
    }

    /**
     * @return string
     */
    protected function getUserProviderModel(): string
    {
        return "";
    }

    /**
     * Get stub of the generated class
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . "/../../stubs/controller.stub";
    }

    /**
     * Get root namespace directory
     * @return string
     */
    protected function getRootPath(): string
    {
        return Console::getInstance()->getRootNamespaceDir();
    }
}
