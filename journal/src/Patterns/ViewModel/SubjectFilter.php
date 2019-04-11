<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class SubjectFilter implements CastsToArray
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $name;
    private $value;
    private $text;

    public function __construct(string $name, string $value, string $text)
    {
        Assertion::notBlank($name);
        Assertion::notBlank($value);
        Assertion::notBlank($text);

        $this->name = $name;
        $this->value = $value;
        $this->text = $text;
    }
}
