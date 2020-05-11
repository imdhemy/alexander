<?php

namespace Macedonia\Alex;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class Command.
 */
abstract class Command extends SymfonyCommand implements StyleInterface
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * The console command help message.
     *
     * @var string
     */
    protected $help = '';

    /**
     * Whether or not the command should be hidden from the list of commands.
     *
     * @var bool
     */
    protected $hidden;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var SymfonyStyle
     */
    protected $io;

    /**
     * Command constructor.
     */
    public function __construct()
    {
        parent::__construct($this->signature);
        $this->setDescription($this->description);
        $this->setHelp($this->help);
        $this->setHidden((bool) $this->hidden);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->io = new SymfonyStyle($input, $output);
        $this->handle();

        return 0;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    abstract public function handle(): void;

    /**
     * Formats a command title.
     *
     * @param string $message
     */
    public function title(string $message)
    {
        $this->io->title($message);
    }

    /**
     * Formats a section title.
     *
     * @param string $message
     */
    public function section(string $message)
    {
        $this->io->section($message);
    }

    /**
     * Formats a list.
     *
     * @param array $elements
     */
    public function listing(array $elements)
    {
        $this->io->listing($elements);
    }

    /**
     * Formats informational text.
     *
     * @param string|array $message
     */
    public function text($message)
    {
        $this->io->text($message);
    }

    /**
     * Formats a success result bar.
     *
     * @param string|array $message
     */
    public function success($message)
    {
        $this->io->success($message);
    }

    /**
     * Formats an error result bar.
     *
     * @param string|array $message
     */
    public function error($message)
    {
        $this->io->error($message);
    }

    /**
     * Formats an warning result bar.
     *
     * @param string|array $message
     */
    public function warning($message)
    {
        $this->io->warning($message);
    }

    /**
     * Formats a note admonition.
     *
     * @param string|array $message
     */
    public function note($message)
    {
        $this->io->note($message);
    }

    /**
     * Formats a note admonition.
     *
     * @param string|array $message
     */
    public function info($message)
    {
        $this->note($message);
    }

    /**
     * Formats a caution admonition.
     *
     * @param string|array $message
     */
    public function caution($message)
    {
        $this->io->caution($message);
    }

    /**
     * Formats a table.
     *
     * @param array $headers
     * @param array $rows
     */
    public function table(array $headers, array $rows)
    {
        $this->io->table($headers, $rows);
    }

    /**
     * Asks a question.
     *
     * @param string        $question
     * @param string|null   $default
     * @param callable|null $validator
     *
     * @return mixed
     */
    public function ask(string $question, ?string $default = null, callable $validator = null)
    {
        return $this->io->ask($question, $default, $validator);
    }

    /**
     * Asks a question with the user input hidden.
     *
     * @param string        $question
     * @param callable|null $validator
     *
     * @return mixed
     */
    public function askHidden(string $question, callable $validator = null)
    {
        return $this->io->askHidden($question, $validator);
    }

    /**
     * Asks for confirmation.
     *
     * @param string $question
     * @param bool   $default
     *
     * @return void
     */
    public function confirm(string $question, bool $default = true)
    {
        return $this->io->confirm($question, $default);
    }

    /**
     * Asks a choice question.
     *
     * @param string          $question
     * @param array           $choices
     * @param string|int|null $default
     *
     * @return mixed
     */
    public function choice(string $question, array $choices, $default = null)
    {
        return $this->io->choice($question, $choices, $default);
    }

    /**
     * Add newline(s).
     *
     * @param int $count
     *
     * @return void
     */
    public function newLine(int $count = 1)
    {
        return $this->io->newLine($count);
    }

    /**
     * Starts the progress output.
     *
     * @param int $max
     */
    public function progressStart(int $max = 0)
    {
        $this->io->progressStart($max);
    }

    /**
     * Advances the progress output X steps.
     *
     * @param int $step
     */
    public function progressAdvance(int $step = 1)
    {
        $this->io->progressStart($step);
    }

    /**
     * Finishes the progress output.
     */
    public function progressFinish()
    {
        $this->io->progressFinish();
    }

    /**
     * @param int   $max
     * @param float $minSecondsBetweenRedraws
     *
     * @return ProgressBar
     */
    public function bar(int $max = 0, float $minSecondsBetweenRedraws = 0.1): ProgressBar
    {
        return new ProgressBar($this->output, $max, $minSecondsBetweenRedraws);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getArgument(string $name): string
    {
        return $this->input->getArgument($name);
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getOption(string $name): string
    {
        return $this->input->getOption($name);
    }
}
