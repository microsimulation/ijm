<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class ArchiveNavLink implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $blockLink;
    private $label;
    private $links;

    private function __construct(BlockLink $blockLink, string $label = null, array $links = null)
    {
        Assertion::nullOrNotBlank($label);
        Assertion::nullOrNotEmpty($links);
        if ($links) {
            Assertion::allIsInstanceOf($links, Link::class);
        }

        $this->blockLink = $blockLink;
        $this->label = $label;
        $this->links = $links;
    }

    public static function basic(BlockLink $blockLink) : self
    {
        return new self($blockLink);
    }

    public static function withLinks(BlockLink $blockLink, string $label, array $links) : self
    {
        return new self($blockLink, $label, $links);
    }

    public function getTemplateName() : string
    {
        return 'patterns/archive-nav-link.mustache';
    }
}
