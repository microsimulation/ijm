<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class CallToAction implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $button;
    private $id;
    private $image;
    private $needsJs;
    private $text;

    public function __construct(string $id, Picture $image, string $text, Button $button, bool $needsJs = false)
    {
        Assertion::notBlank($id);
        Assertion::notBlank($text);

        $this->id = $id;
        if ($needsJs) {
            $this->needsJs = $needsJs;
        }
        $this->image = $image;
        $this->text = $text;
        $this->button = FlexibleViewModel::fromViewModel($button)
            ->withProperty('classes', "{$button['classes']} call-to-action__button");
    }

    public function getTemplateName() : string
    {
        return 'patterns/call-to-action.mustache';
    }
}
