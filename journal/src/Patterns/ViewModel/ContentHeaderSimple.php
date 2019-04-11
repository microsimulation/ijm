<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class ContentHeaderSimple implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $title;
    private $strapline;

    public function __construct(string $title, string $strapline = null)
    {
        Assertion::notBlank($title);

        $this->title = $title;
        $this->strapline = $strapline;
    }

    public function getTemplateName() : string
    {
        return 'patterns/content-header-simple.mustache';
    }
}
