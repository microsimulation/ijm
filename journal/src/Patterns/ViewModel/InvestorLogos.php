<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class InvestorLogos implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $logos;

    public function __construct(Picture ...$logos)
    {
        Assertion::notEmpty($logos);

        $this->logos = $logos;
    }

    public function getTemplateName() : string
    {
        return 'patterns/investor-logos.mustache';
    }
}
