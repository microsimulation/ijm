<?php

namespace Microsimulation\Journal\Patterns\PatternRenderer;

use Microsimulation\Journal\Patterns\PatternRenderer;
use Microsimulation\Journal\Patterns\ViewModel;

final class CallbackPatternRenderer implements PatternRenderer
{
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function render(ViewModel ...$viewModels) : string
    {
        return call_user_func($this->callback, ...$viewModels);
    }
}
