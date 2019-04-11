<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class ListingAnnotationTeasers implements ViewModel
{
    use ListingConstructors;
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $items;
    private $id;
    private $heading;
    private $pagination;

    private function __construct(array $items, string $id = null, ListHeading $heading = null, Pager $pagination = null)
    {
        Assertion::notEmpty($items);
        Assertion::allIsInstanceOf($items, AnnotationTeaser::class);

        $this->items = $items;
        $this->id = $id;
        $this->heading = $heading;
        $this->pagination = $pagination;
    }

    public function getTemplateName() : string
    {
        return 'patterns/listing-annotation-teasers.mustache';
    }
}
