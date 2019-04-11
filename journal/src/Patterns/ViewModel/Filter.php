<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class Filter implements CastsToArray
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $isChecked;
    private $label;
    private $results;
    private $name;
    private $value;

    public function __construct(bool $isChecked, string $label, int $results, string $name, string $value = null)
    {
        Assertion::notBlank($label);

        $this->isChecked = $isChecked;
        $this->label = $label;
        $this->results = number_format($results);
        $this->name = $name;
        $this->value = $value;
    }
}
