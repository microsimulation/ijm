<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class Carousel implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $heading;
    private $items;

    public function __construct(array $items, ListHeading $heading)
    {
        Assertion::notEmpty($items);

        $this->heading = $heading;
        $this->items = $items;
    }

    public function getTemplateName() : string
    {
        return 'patterns/carousel.mustache';
    }
}
