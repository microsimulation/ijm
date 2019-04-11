<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class Picture implements ViewModel, IsCaptioned
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $fallback;
    private $sources;

    public function __construct(array $sources, Image $fallback)
    {
        Assertion::allIsArray($sources);

        $this->sources = $sources;
        $this->fallback = $fallback;
    }

    public function getTemplateName() : string
    {
        return 'patterns/picture.mustache';
    }
}
