<?php

namespace Microsimulation\Journal\Helper;

use Microsimulation\Journal\Patterns\PatternRenderer;

trait HasPatternRenderer
{
    abstract protected function getPatternRenderer() : PatternRenderer;
}
