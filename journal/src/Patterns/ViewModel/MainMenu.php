<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class MainMenu implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $links;
    private $listHeading;

    public function __construct(array $links)
    {
        Assertion::notEmpty($links);
        Assertion::allIsInstanceOf($links, Link::class);

        $this->links = ['items' => $links];
        $this->listHeading = new ListHeading('Menu');
    }

    public function getTemplateName() : string
    {
        return 'patterns/main-menu.mustache';
    }
}
