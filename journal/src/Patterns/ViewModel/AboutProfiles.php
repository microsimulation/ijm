<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class AboutProfiles implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $heading;
    private $compact;
    private $items;

    public function __construct(array $items, ListHeading $heading = null, bool $compact = false)
    {
        Assertion::notEmpty($items);
        Assertion::allIsInstanceOf($items, AboutProfile::class);

        $this->heading = $heading;
        if ($compact) {
            $this->compact = $compact;
        }
        $this->items = $items;
    }

    public function getTemplateName() : string
    {
        return 'patterns/about-profiles.mustache';
    }
}
