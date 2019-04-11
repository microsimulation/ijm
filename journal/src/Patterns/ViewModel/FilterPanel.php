<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class FilterPanel implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $title;
    private $filterGroups;
    private $button;

    public function __construct(
        string $title,
        array $filterGroups,
        Button $button
    ) {
        Assertion::notBlank($title);
        Assertion::notEmpty($filterGroups);
        Assertion::allIsInstanceOf($filterGroups, FilterGroup::class);

        $this->title = $title;
        $this->filterGroups = $filterGroups;
        $this->button = $button;
    }

    public function getTemplateName() : string
    {
        return 'patterns/filter-panel.mustache';
    }
}
