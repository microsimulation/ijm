<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class Code implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $code;

    public function __construct(string $code)
    {
        Assertion::notBlank($code);

        $this->code = $code;
    }

    public function getTemplateName() : string
    {
        return 'patterns/code.mustache';
    }
}
