<?php

namespace Microsimulation\Journal\Patterns;

interface PatternRenderer
{
    public function render(ViewModel ...$viewModels) : string;
}
