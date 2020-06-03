<?php

namespace Macedonia\Alex\Commands;

use function basename;
use Illuminate\Filesystem\Filesystem;
use function in_array;
use Macedonia\Alex\Command;
use function realpath;
use function str_replace;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

/**
 * Class InstallWordPressTestsCommand.
 */
class InstallWordPressTestsCommand extends Command
{
    const TMP_WORDPRESS_THEMES = './tmp/wordpress/wp-content/themes/%s';

    const EXCLUDED_DIR = [
        './tmp',
    ];

    /**
     * @var bool
     */
    private $skipDataBaseCreation;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:tests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates the required files to run Wordpress PHPUnit tests';

    /**
     * The console command help message.
     *
     * @var string
     */
    protected $help = 'use --database=false to skip creation of a test database.';
    private $file_system;

    /**
     * InstallWordPressTestsCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->skipDataBaseCreation = false;
        $this->file_system = new Filesystem();
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        parent::configure();
        $this->addOption(
            'database',
            null,
            InputOption::VALUE_OPTIONAL,
            'Create database or not',
            InputOption::VALUE_NONE
        );
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $installDb = strtolower($this->input->getOption('database'));
        $this->skipDataBaseCreation = $installDb === 'false';

        $this->title('Install wordpress tests');
        $this->info('This commands requires administrator credentials');
        $this->useTerminalAsAdmin();
        $this->runCommand();
        $this->copyTheme();
    }

    /**
     * Use terminal as administrator.
     *
     * @void
     */
    private function useTerminalAsAdmin()
    {
        if (strtoupper(PHP_OS) === 'WIN') {
            $this->error('This command is not supported on Windows.');
        }
        (new Process(['sudo', 'su']))->run();
    }

    /**
     * Run console command.
     */
    private function runCommand()
    {
        $this->info('Process starts ..');
        $process = new Process($this->getCommandAttributes());
        $process->run();
        if ($process->isSuccessful()) {
            $this->success($process->getOutput());

            return;
        }
        $this->error($process->getErrorOutput());
    }

    /**
     * Get command attributes.
     *
     * @return array
     */
    private function getCommandAttributes(): array
    {
        $database = env('DB_NAME', 'wordpress_test');
        $user = env('DB_USER', 'root');
        $password = env('DB_PASSWORD', '');
        $host = env('DB_HOST', 'localhost');
        $version = env('WP_VERSION', 'latest');
        $command = realpath(__DIR__.'/../../bin/install-wp-tests.sh');
        $commandAttributes = [$command, $database, $user, $password, $host, $version];
        if ($this->skipDataBaseCreation) {
            $commandAttributes[] = 'true';
        }

        return $commandAttributes;
    }

    /**
     * Copies theme files the generated wordpress directory.
     */
    private function copyTheme(): void
    {
        $themeDir = $this->makeThemeDirectory();
        $this->copyDirectories($themeDir);
        $this->copyFiles($themeDir);
    }

    /**
     * @return string
     */
    private function makeThemeDirectory(): string
    {
        $themeName = env('THEME_NAME', 'alexander');
        $themeDirectory = sprintf(self::TMP_WORDPRESS_THEMES, $themeName);

        if (!$this->file_system->exists($themeDirectory)) {
            $this->file_system->makeDirectory($themeDirectory);
        }

        return $themeDirectory;
    }

    /**
     * @param string $themeDir
     */
    private function copyDirectories(string $themeDir): void
    {
        $directories = $this->file_system->directories('.');
        foreach ($directories as $directory) {
            if (!in_array($directory, self::EXCLUDED_DIR)) {
                $newPath = $themeDir.str_replace('.', '', $directory);
                $this->file_system->copyDirectory($directory, $newPath);
            }
        }
    }

    /**
     * @param string $themeDir
     */
    private function copyFiles(string $themeDir): void
    {
        $files = $this->file_system->files(realpath('.'));
        foreach ($files as $file) {
            $path = $file->getRealPath();
            $target = $themeDir.'/'.basename($path);
            $this->file_system->copy($path, $target);
        }
    }
}
