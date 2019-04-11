<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class SelectOption implements CastsToArray
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $value;
    private $displayValue;
    private $selected;

    public function __construct(string $value, string $displayValue, bool $selected = false)
    {
        $this->value = $value;
        $this->displayValue = $displayValue;
        $this->selected = $selected;
    }
}
