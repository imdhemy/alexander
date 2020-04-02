<?php


namespace Macedonia\Alex\Commands;


use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Macedonia\Alex\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class GeneratorCommand
 * @package Macedonia\Alex\Commands
 */
class GeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "foo:bar";

    /**
     * Files system instance
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * The type of the generated class
     *
     * @var string
     */
    protected $type;

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
        return "User";
    }

    /**
     * Get stub of the generated class
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . "/../../stubs/console.stub";
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        // TODO: qualifyClass
        $name = "Alexander\\" . $this->getInputName();
        // TODO: get path
        $path = __DIR__ . "/Generated/{$name}.php";
        // TODO: check if exists and handle force option
        $this->makeDirectory($path);
        $this->files->put($path, $this->buildClass($name));
        $this->success($this->type . ' created successfully.');
    }


    /**
     * Determine if the class already exists.
     *
     * @param string $rawName
     * @return bool
     */
    protected function alreadyExists($rawName): bool
    {
        return false;
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param string $path
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
     * @return string
     * @throws FileNotFoundException
     */
    protected function buildClass(string $name): string
    {
        $stub = $this->files->get($this->getStub());
        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param string $stub
     * @param string $name
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
     * @return string
     */
    protected function replaceClass(string $stub, string $name): string
    {
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);
        return str_replace(['DummyClass', '{{ class }}', '{{class}}'], $class, $stub);
    }

    /**
     * Get the class name from input
     * @return string
     */
    public function getInputName(): string
    {
        return $this->getArgument('name');
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param string $name
     * @return string
     */
    protected function getNamespace(string $name): string
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }
}
