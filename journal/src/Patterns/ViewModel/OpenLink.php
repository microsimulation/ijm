<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class OpenLink implements CastsToArray
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $uri;
    private $width;
    private $height;

    public function __construct(string $uri, int $width, int $height)
    {
        Assertion::notBlank($uri);
        Assertion::min($width, 1);
        Assertion::min($height, 1);

        $this->uri = $uri;
        $this->width = $width;
        $this->height = $height;
    }
}
