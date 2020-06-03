<?php

namespace Macedonia\Alex;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class GeneratorCommand.
 */
abstract class GeneratorCommand extends Command
{
    /**
     * Files system instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * The type of the generated class.
     *
     * @var string
     */
    protected $type = 'Class';

    /**
     * GeneratorCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->files = new Filesystem();
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        parent::configure();
        $this->addArgument('name', InputArgument::REQUIRED);
        $this->addOption(
            'force',
            'f',
            InputOption::VALUE_OPTIONAL,
            'Should override the existent class?',
            false
        );
    }

    /**
     * Execute the console command.
     *
     * @throws FileNotFoundException
     *
     * @return void
     */
    public function handle(): void
    {
        $name = $this->qualifyClass($this->getInputName());
        $path = $this->getPath($name);

        if ($this->files->exists($path) && !$this->shouldForce()) {
            $this->error("{$this->type} already exists.");

            return;
        }

        $this->makeDirectory($path);
        $this->files->put($path, $this->buildClass($name));
        $this->success($this->type.' created successfully.');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    abstract protected function getDefaultNamespace(string $rootNamespace): string;

    /**
     * Get the root namespace of the class.
     *
     * @return string
     */
    abstract protected function getRootNamespace(): string;

    /**
     * @return string
     */
    abstract protected function getUserProviderModel(): string;

    /**
     * Get stub of the generated class.
     *
     * @return string
     */
    abstract protected function getStub(): string;

    /**
     * Get root namespace directory.
     *
     * @return string
     */
    abstract protected function getRootPath(): string;

    /**
     * Build the directory for the class if necessary.
     *
     * @param string $path
     *
     * @return string
     */
    protected function makeDirectory($path): string
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @throws FileNotFoundException
     *
     * @return string
     */
    protected function buildClass(string $name): string
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param $stub
     * @param $name
     *
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name): self
    {
        $searches = [
            ['DummyNamespace', 'DummyRootNamespace', 'NamespacedDummyUserModel'],
            ['{{ namespace }}', '{{ rootNamespace }}', '{{ namespacedUserModel }}'],
            ['{{namespace}}', '{{rootNamespace}}', '{{namespacedUserModel}}'],
        ];

        foreach ($searches as $search) {
            $stub = str_replace(
                $search,
                [$this->getNamespace($name), $this->getRootNamespace(), $this->getUserProviderModel()],
                $stub
            );
        }

        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     *
     * @return string
     */
    protected function replaceClass(string $stub, string $name): string
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace(['DummyClass', '{{ class }}', '{{class}}'], $class, $stub);
    }

    /**
     * Get the class name from input.
     *
     * @return string
     */
    public function getInputName(): string
    {
        return $this->getArgument('name');
    }

    /**
     * Determine if the creation of the class should be forced.
     *
     * @return bool
     */
    public function shouldForce(): bool
    {
        return $this->input->hasParameterOption('--force') || $this->input->hasParameterOption('-f');
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getNamespace(string $name): string
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param string $name
     *
     * @return string
     */
    protected function qualifyClass(string $name): string
    {
        $name = ltrim($name, '\\/');
        $rootNamespace = $this->getRootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        $name = str_replace('/', '\\', $name);

        return $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name
        );
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getPath(string $name): string
    {
        $name = Str::replaceFirst($this->getRootNamespace(), '', $name);

        return sprintf('%s/%s.php', $this->getRootPath(), str_replace('\\', '/', $name));
    }
}
