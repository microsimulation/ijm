<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class SeeMoreLink implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    protected $name;

    protected $url;

    public function __construct(Link $link, bool $isInline = false)
    {
        $this->name = $link['name'];
        $this->url = $link['url'];
        $this->isInline = $isInline;
    }

    public function getTemplateName() : string
    {
        return 'patterns/see-more-link.mustache';
    }
}
