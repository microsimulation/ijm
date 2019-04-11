<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class ReferenceList implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $references;

    public function __construct(ReferenceListItem ...$references)
    {
        Assertion::notEmpty($references);

        $this->references = $references;
    }

    public function getTemplateName() : string
    {
        return 'patterns/reference-list.mustache';
    }
}
