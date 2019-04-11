<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class FilterGroup implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $title;
    private $filters;

    public function __construct(string $title, array $filters)
    {
        Assertion::notBlank($title);
        Assertion::allIsInstanceOf($filters, Filter::class);

        $this->title = $title;
        $this->filters = $filters;
    }

    public function getTemplateName() : string
    {
        return 'patterns/filter-group.mustache';
    }
}
