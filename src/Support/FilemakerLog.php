<?php

namespace Hyyppa\Filemaker\Support;

class FilemakerLog
{
    /**
     * @var bool
     */
    protected $debugMode = false;

    /**
     * @var array|int
     */
    protected $counters = [];

    /**
     * @param array $message
     */
    public function info(...$message): void
    {
        if ($this->debugMode) {
            dump($message);
        }
        //debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 )
    }

    public function enableDebug(): void
    {
        $this->debugMode = true;
    }

    /**
     * @param string $counter
     *
     * @return int
     */
    public function increment(string $counter): int
    {
        if (! array_key_exists($counter, $this->counters)) {
            $this->counters[$counter] = 0;
        }

        return ++$this->counters[$counter];
    }

    /**
     * @param string $counter
     * @param string $label
     *
     * @return string
     */
    public function getCounter(string $counter, string $label = ''): string
    {
        if ('' !== $label) {
            $label .= ': ';
        }

        return $label.$this->counters[$counter];
    }
}
