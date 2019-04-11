<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class Math implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $math;
    private $id;
    private $label;

    public function __construct(string $math, string $id = null, string $label = null)
    {
        Assertion::regex($math, '/^<math>[\s\S]+<\/math>$/');

        $this->math = $math;
        $this->id = $id;
        $this->label = $label;
    }

    public function getTemplateName() : string
    {
        return 'patterns/math.mustache';
    }
}
