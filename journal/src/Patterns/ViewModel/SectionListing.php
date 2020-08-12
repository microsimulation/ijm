<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class SectionListing implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $id;
    private $sections;
    private $listHeading;
    private $singleLine;
    private $labelledBy;
    private $context;

    public function __construct(
        string $id, 
        array $sections, 
        ListHeading $listHeading, 
        bool $singleLine = false, 
        string $labelledBy = null,
        array $context = []
    ) {
        Assertion::notBlank($id);
        Assertion::allIsInstanceOf($sections, Link::class);
        Assertion::notEmpty($sections);

        $this->id = $id;
        $this->sections = $sections;
        $this->singleLine = $singleLine;
        $this->listHeading = $listHeading;
        $this->labelledBy = $labelledBy;
        $this->context = $context;
    }

    public function getTemplateName() : string
    {
        return 'patterns/section-listing.mustache';
    }
}
