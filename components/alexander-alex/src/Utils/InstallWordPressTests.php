<?php

namespace Macedonia\Alex\Utils;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem as FSContract;
use Illuminate\Filesystem\Filesystem;

/**
 * Class InstallWordPressTests.
 */
class InstallWordPressTests
{
    /**
     * @var Archive
     */
    private $archive;

    /**
     * @var FSContract
     */
    private $fileSystem;

    /**
     * @var string
     */
    private $tmpDir;

    /**
     * InstallWordPressTests constructor.
     */
    public function __construct()
    {
        $this->archive = new Archive();
        $this->tmpDir = realpath('.').'/tmp';
        $this->fileSystem = new Filesystem();
    }

    public function ExtractFiles(): void
    {
        $this->archive->unzip($this->tmpDir.'/wordpress.zip', $this->tmpDir);
        $this->archive->unzip($this->tmpDir.'/wordpress-tests-lib.zip', $this->tmpDir);
    }

    /**
     * @throws FileNotFoundException
     */
    public function updateConfig(): void
    {
        $stub = $this->replacePlaceholders();
        $this->fileSystem->put($this->tmpDir.'/wordpress-tests-lib/wp-tests-config.php', $stub);
    }

    /**
     * @throws FileNotFoundException
     *
     * @return string|string[]
     */
    private function replacePlaceholders()
    {
        $stub = $this->fileSystem->get($this->tmpDir.'/wp-tests-config.stub');
        $data = [
            'ALEXANDER_WP_CORE_PATH'   => $this->tmpDir.'/wordpress/',
            'ALEXANDER_WP_DB_NAME'     => env('DB_NAME'),
            'ALEXANDER_WP_DB_USER'     => env('DB_USER'),
            'ALEXANDER_WP_DB_PASSWORD' => env('DB_PASSWORD'),
            'ALEXANDER_WP_DB_HOST'     => env('DB_HOST'),
        ];

        foreach ($data as $placeholder => $value) {
            $stub = str_replace($placeholder, $value, $stub);
        }

        return $stub;
    }
}
