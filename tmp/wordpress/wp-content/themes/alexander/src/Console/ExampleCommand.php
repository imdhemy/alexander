<?php

namespace Alexander\Console;

use Illuminate\Support\Collection;
use Macedonia\Alex\Command;

/**
 * Class ExampleCommand.
 */
class ExampleCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'inspire';

    /**
     * @var Collection
     */
    private $quotes;

    /**
     * ExampleCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->quotes = new Collection([
            "\"Programming isn't about what you know; it's about what you can figure out.” - Chris Pine",
            'The only way to learn a new programming language is by writing programs in it." - Dennis Ritchie',
            "Sometimes it's better to leave something alone, to pause, and that's very true of programming.\" - Joyce Wheeler",
            '"In some ways, programming is like painting. You start with a blank canvas and certain basic raw materials. You use a combination of science, art, and craft to determine what to do with them." - Andrew Hunt',
            '"Testing leads to failure, and failure leads to understanding." - Burt Rutan',
            '"The best error message is the one that never shows up." - Thomas Fuchs',
            "“The most damaging phrase in the language is.. it's always been done this way” - Grace Hopper",
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->success($this->quotes->random());
    }
}
