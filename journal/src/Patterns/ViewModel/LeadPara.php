<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class LeadPara implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    protected $text;

    public function __construct(string $text, string $id = null)
    {
        Assertion::notBlank($text);

        $this->text = $text;
        $this->id = $id;
    }

    public function getTemplateName() : string
    {
        return 'patterns/lead-para.mustache';
    }
}
