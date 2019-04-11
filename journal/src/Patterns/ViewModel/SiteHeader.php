<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class SiteHeader implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $homePagePath;
    private $primaryLinks;
    private $secondaryLinks;
    private $searchBox;

    public function __construct(string $homePagePath, SiteHeaderNavBar $primaryLinks, SiteHeaderNavBar $secondaryLinks, SearchBox $searchBox = null)
    {
        Assertion::notBlank($homePagePath);

        $this->homePagePath = $homePagePath;
        $this->primaryLinks = $primaryLinks;
        $this->secondaryLinks = $secondaryLinks;
        if ($searchBox) {
            $this->searchBox = FlexibleViewModel::fromViewModel($searchBox)->withProperty('inContentHeader', true);
        }
    }

    public function getTemplateName() : string
    {
        return 'patterns/site-header.mustache';
    }
}
