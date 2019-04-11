<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class InlineProfile implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $image;
    private $text;

    public function __construct(Picture $image, string $text)
    {
        Assertion::notBlank($text);

        $this->image = $image;
        $this->text = $text;
    }

    public function getTemplateName() : string
    {
        return 'patterns/inline-profile.mustache';
    }
}
