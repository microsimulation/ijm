<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class StatisticCollection implements ViewModel
{
    use ArrayFromProperties;
    use ArrayAccessFromProperties;

    private $stats;

    public function __construct(Statistic ...$stats)
    {
        Assertion::notEmpty($stats);

        $this->stats = $stats;
    }

    public function getTemplateName() : string
    {
        return 'patterns/statistic-collection.mustache';
    }
}
