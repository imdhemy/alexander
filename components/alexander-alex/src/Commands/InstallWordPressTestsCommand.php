<?php

namespace Macedonia\Alex\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Macedonia\Alex\Command;
use Macedonia\Alex\Utils\WordPressTestsInstaller;

/**
 * Class InstallWordPressTestsCommand.
 */
class InstallWordPressTestsCommand extends Command
{
    /**
     * Tmp wordpress theme directory.
     */
    const TMP_WORDPRESS_THEMES = './tmp/wordpress/wp-content/themes/%s';

    /**
     * Directories that are excluded from the copy process.
     */
    const EXCLUDED_DIR = [
        './tmp',
    ];

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
    protected $help = 'Generates the required files to run Wordpress PHPUnit tests';

    /**
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * @var WordPressTestsInstaller
     */
    private $installer;

    /**
     * InstallWordPressTestsCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->fileSystem = new Filesystem();
        $this->installer = new WordPressTestsInstaller();
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
        $this->title('Install wordpress tests');

        $this->extractFiles();

        $this->updateConfigs();

        $this->installTheme();

        $this->success('There is nothing impossible to him who will try. ~ Alexander the Great');
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

        if (!$this->fileSystem->exists($themeDirectory)) {
            $this->fileSystem->makeDirectory($themeDirectory);
        }

        return $themeDirectory;
    }

    /**
     * @param string $themeDir
     */
    private function copyDirectories(string $themeDir): void
    {
        $directories = $this->fileSystem->directories('.');
        foreach ($directories as $directory) {
            if (!in_array($directory, self::EXCLUDED_DIR)) {
                $newPath = $themeDir.str_replace('.', '', $directory);
                $this->fileSystem->copyDirectory($directory, $newPath);
            }
        }
    }

    /**
     * @param string $themeDir
     */
    private function copyFiles(string $themeDir): void
    {
        $files = $this->fileSystem->files(realpath('.'));
        foreach ($files as $file) {
            $path = $file->getRealPath();
            $target = $themeDir.'/'.basename($path);
            $this->fileSystem->copy($path, $target);
        }
    }

    private function extractFiles(): void
    {
        $this->info('Extracting wordpress files..');
        $this->installer->extractFiles();
        $this->success('Extracted wordpress files.');
    }

    /**
     * @throws FileNotFoundException
     */
    private function updateConfigs(): void
    {
        $this->info('Updating configuration based on environment values.');
        $this->installer->updateConfig();
        $this->success('Updated configuration successfully.');
    }

    private function installTheme(): void
    {
        $this->info('Installing theme..');
        $this->copyTheme();
        $this->success('Theme installed successfully..');
    }
}
