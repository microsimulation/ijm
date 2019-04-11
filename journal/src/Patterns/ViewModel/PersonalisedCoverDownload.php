<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class PersonalisedCoverDownload implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $text;
    private $buttonCollection;

    public function __construct(array $text, ButtonCollection $buttonCollection)
    {
        Assertion::notEmpty($text);
        Assertion::allIsInstanceOf($text, Paragraph::class);

        if (!$buttonCollection['centered']) {
            $buttonCollection = FlexibleViewModel::fromViewModel($buttonCollection)
                ->withProperty('centered', true);
        }

        $this->text = $text;
        $this->buttonCollection = $buttonCollection;
    }

    public function getTemplateName() : string
    {
        return 'patterns/personalised-cover-download.mustache';
    }
}
