<?php


namespace Macedonia\Alex\Commands;


use Macedonia\Alex\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

/**
 * Class InstallWordPressTestsCommand
 * @package Macedonia\Alex\Commands
 */
class InstallWordPressTestsCommand extends Command
{
    /**
     * @var bool
     */
    private $skipDataBaseCreation;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "make:tests";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generates the required files to run Wordpress PHPUnit tests";


    /**
     * The console command help message
     *
     * @var string
     */
    protected $help = "use --database=false to skip creation of a test database.";

    /**
     * InstallWordPressTestsCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->skipDataBaseCreation = false;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        parent::configure();
        $this->addOption(
            "database",
            null,
            InputOption::VALUE_OPTIONAL,
            "Create database or not",
            InputOption::VALUE_NONE);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $installDb = strtolower($this->input->getOption('database'));
        $this->skipDataBaseCreation = $installDb === "false";

        $this->title("Install wordpress tests");
        $this->info("This commands requires administrator credentials");
        $this->useTerminalAsAdmin();
        $this->runCommand();
    }

    /**
     * Use terminal as administrator
     * @void
     */
    private function useTerminalAsAdmin()
    {
        if (strtoupper(PHP_OS) === 'WIN') {
            $this->error("This command is not supported on Windows.");
        }
        (new Process(["sudo", "su"]))->run();
    }

    /**
     *
     */
    private function runCommand()
    {
        $this->info("Process starts ..");
        $process = new Process($this->getCommandAttributes());
        $process->run();
        if ($process->isSuccessful()) {
            $this->success($process->getOutput());
            return;
        }
        $this->error($process->getErrorOutput());
    }

    /**
     * Get command attributes
     * @return array
     */
    private function getCommandAttributes(): array
    {
        $database = env("DB_NAME", "wordpress_test");
        $user = env("DB_USER", "root");
        $password = env("DB_PASSWORD", "");
        $host = env("DB_Host", "localhost");
        $version = env("WP_VERSION", "latest");
        $command = realpath(__DIR__ . "/../../bin/install-wp-tests.sh");
        $commandAttributes = [$command, $database, $user, $password, $host, $version];
        if ($this->skipDataBaseCreation) {
            $commandAttributes[] = "true";
        }
        return $commandAttributes;
    }
}
