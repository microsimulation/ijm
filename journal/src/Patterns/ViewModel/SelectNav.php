<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class SelectNav implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $route;
    private $select;
    private $button;

    public function __construct(string $route, Select $select, Button $button)
    {
        Assertion::notBlank($route);

        $this->route = $route;
        $this->select = $select;
        $this->button = $button;
    }

    public function getTemplateName() : string
    {
        return 'patterns/select-nav.mustache';
    }
}
