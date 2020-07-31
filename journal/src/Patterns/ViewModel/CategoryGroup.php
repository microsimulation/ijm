<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;
use InvalidArgumentException;

final class CategoryGroup implements ViewModel
{
    use ListingConstructors;
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $items;
    private $id;
    private $heading;
    private $pagination;
    private $seeMoreLink;
    private $highlights;

    private function __construct(
        array $items,
        string $id = null,
        ListHeading $heading = null,
        Pager $pagination = null,
        SeeMoreLink $seeMoreLink = null,
        bool $highlights = false
    ) {
        $this->items = $items;
        $this->id = $id;
        $this->heading = $heading;
        $this->pagination = $pagination;
        $this->seeMoreLink = $seeMoreLink;
        $this->highlights = $highlights;
    }

    public static function forHighlights(array $items, ListHeading $heading, string $id) : ViewModel
    {
        $viewModel = new static($items, $id, $heading, null, null, true);

        return $viewModel;
    }

    public function getTemplateName() : string
    {
        return 'patterns/collection-category-group.mustache';
    }
}
