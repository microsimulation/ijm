<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class ContextLabel implements CastsToArray
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $list;

    public function __construct(Link ...$list)
    {
        Assertion::notEmpty($list);

        $this->list = $list;
    }
}
