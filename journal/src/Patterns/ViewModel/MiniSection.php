<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class MiniSection implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $body;
    private $listHeading;

    public function __construct(string $body, ListHeading $listHeading = null)
    {
        Assertion::notBlank($body);

        $this->body = $body;
        $this->listHeading = $listHeading;
    }

    public function getTemplateName() : string
    {
        return 'patterns/mini-section.mustache';
    }
}
