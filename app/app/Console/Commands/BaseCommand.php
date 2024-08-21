<?php

namespace App\Console\Commands;

use App\Services\Stopwatch;
use Illuminate\Console\Command;

abstract class BaseCommand extends Command
{
    protected Stopwatch $stopwatch;

    public function __construct()
    {
        parent::__construct();
        $this->stopwatch = new Stopwatch();
    }

    public function line($string, $style = null, $verbosity = null): void
    {
        parent::line('[!] ' . $string, $style, $verbosity);
    }

    public function printCommandCompletionMessage(): void
    {
        $customOptions = $this->options();
        $laravelDefaultOptions = $this->getApplication()->getDefinition()->getOptions();
        $customOptions = array_diff_key($customOptions, $laravelDefaultOptions);
        $options = '';
        if ($customOptions) {
            $customOptions = array_filter($customOptions);
            $customOptions = array_keys($customOptions);
            $customOptions = array_map(fn ($item) => '--' . $item, $customOptions);
            $options = implode(' ', $customOptions);
        }
        $this->line(
            $this->name . ($options ? ' ' . $options : '') . ' completed in ' . $this->stopwatch->getSeconds() .' sec.'
        );
    }
}
