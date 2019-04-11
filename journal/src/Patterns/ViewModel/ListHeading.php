<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class ListHeading implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $heading;
    private $headingId;

    public function __construct(string $heading, string $headingId = null)
    {
        Assertion::notBlank($heading);

        $this->heading = $heading;
        $this->headingId = $headingId;
    }

    public function getTemplateName() : string
    {
        return 'patterns/list-heading.mustache';
    }
}
