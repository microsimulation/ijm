<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class ButtonCollection implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $buttons;
    private $centered;
    private $compact;

    public function __construct(array $buttons, bool $centered = false, bool $compact = false)
    {
        Assertion::notEmpty($buttons);
        Assertion::allIsInstanceOf($buttons, Button::class);

        $this->buttons = $buttons;
        if ($centered) {
            $this->centered = $centered;
        }
        if ($compact) {
            $this->compact = $compact;
        }
    }

    public function getTemplateName() : string
    {
        return 'patterns/button-collection.mustache';
    }
}
