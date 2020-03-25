<?php


namespace Alexander\Commands;


use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Console
 * @package Alexander\Commands
 */
abstract class Command extends SymfonyCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "command:name";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "";

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * Command constructor.
     *
     * @param  string|null  $name
     */
    public function __construct(string $name = null)
    {
        parent::__construct($this->signature);
        $this->setDescription($this->description);
    }


    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     *
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input  = $input;
        $this->output = $output;
        $this->handle();

        return 0;
    }


    /**
     * Execute the console command.
     *
     * @return void
     */
    abstract function handle(): void;
}
