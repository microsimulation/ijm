<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class ListingProfileSnippets implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $seeMoreLink;
    private $heading;
    private $items;

    private function __construct(array $items, ListHeading $heading = null, SeeMoreLink $seeMoreLink = null)
    {
        Assertion::notEmpty($items);
        Assertion::allIsInstanceOf($items, ProfileSnippet::class);

        $this->seeMoreLink = $seeMoreLink;
        $this->heading = $heading;
        $this->items = $items;
    }

    public static function basic(array $items, ListHeading $heading = null)
    {
        return new static ($items, $heading);
    }

    public static function withSeeMoreLink(array $items, SeeMoreLink $seeMoreLink, ListHeading $heading = null)
    {
        return new static($items, $heading, $seeMoreLink);
    }

    public function getTemplateName() : string
    {
        return 'patterns/listing-profile-snippets.mustache';
    }
}
