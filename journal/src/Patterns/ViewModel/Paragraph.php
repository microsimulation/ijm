<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class Paragraph implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $text;

    public function __construct(string $text)
    {
        Assertion::notBlank($text);

        $this->text = $text;
    }

    public function getTemplateName() : string
    {
        return 'patterns/paragraph.mustache';
    }
}
