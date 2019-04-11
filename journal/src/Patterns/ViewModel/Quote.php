<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class Quote implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $quote;
    private $cite;

    public function __construct(
        string $quote,
        string $cite = null
    ) {
        Assertion::notBlank($quote);

        $this->quote = $quote;
        $this->cite = $cite;
    }

    public function getTemplateName() : string
    {
        return 'patterns/quote.mustache';
    }
}
