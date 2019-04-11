<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class SectionListingLink implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $text;
    private $targetFragmentId;

    public function __construct(string $text, string $targetFragmentId)
    {
        Assertion::notBlank($text);
        Assertion::notBlank($targetFragmentId);

        $this->text = $text;
        $this->targetFragmentId = $targetFragmentId;
    }

    public function getTemplateName() : string
    {
        return 'patterns/section-listing-link.mustache';
    }
}
