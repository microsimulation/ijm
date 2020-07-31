<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class Link implements CastsToArray
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $name;
    private $url = null;
    private $isCurrent;
    private $attributes;

    public function __construct(string $name, string $url = null, bool $isCurrent = false, array $attributes = [])
    {
        // Assertion::notBlank($name);

        $this->name = $name;
        $this->url = $url ?? false;
        if ($isCurrent) {
            $this->isCurrent = true;
        }
        $this->attributes = array_reduce(array_keys($attributes), function (array $carry, string $key) use ($attributes) {
            $carry[] = ['key' => $key, 'value' => $attributes[$key]];

            return $carry;
        }, []);
    }
}
