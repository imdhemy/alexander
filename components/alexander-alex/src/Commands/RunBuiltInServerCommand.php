<?php

namespace Macedonia\Alex\Commands;

use Macedonia\Alex\Command;

/**
 * Class RunBuiltInServerCommand.
 */
class RunBuiltInServerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve the application on the PHP development server';

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $root_directory = './tmp/wordpress';
        $this->info('Alexander development server started: http://localhost:8080');
        $output = shell_exec("php -S localhost:8080 -t $root_directory");
        $this->newLine($output);
    }
}
