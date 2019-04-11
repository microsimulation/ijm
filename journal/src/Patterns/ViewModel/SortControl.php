<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class SortControl implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $options;

    public function __construct(array $options)
    {
        Assertion::notEmpty($options);
        Assertion::allIsInstanceOf($options, SortControlOption::class);

        $this->options = $options;
    }

    public function getTemplateName() : string
    {
        return 'patterns/sort-control.mustache';
    }
}
