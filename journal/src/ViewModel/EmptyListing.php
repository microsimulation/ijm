<?php

namespace Microsimulation\Journal\ViewModel;

use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;
use Microsimulation\Journal\Patterns\ViewModel\ListHeading;

final class EmptyListing implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $heading;
    private $text;

    public function __construct(ListHeading $heading = null, string $text)
    {
        $this->heading = $heading;
        $this->text = $text;
    }

    public function getTemplateName() : string
    {
        return 'patterns/empty-listing.mustache';
    }
}
