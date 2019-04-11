<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class DecisionLetterHeader implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $mainText;
    private $hasProfiles;
    private $profiles;

    public function __construct(string $mainText, array $profiles = [])
    {
        Assertion::notBlank($mainText);
        Assertion::allIsInstanceOf($profiles, ProfileSnippet::class);

        $this->hasProfiles = !empty($profiles) ? true : null;
        $this->profiles = $profiles;

        $this->mainText = $mainText;
    }

    public function getTemplateName() : string
    {
        return 'patterns/decision-letter-header.mustache';
    }
}
