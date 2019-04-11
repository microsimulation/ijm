<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class BlockLink implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $text;
    private $url;
    private $image;

    public function __construct(Link $link, Picture $image = null)
    {
        $this->text = $link['name'];
        $this->url = $link['url'];
        $this->image = $image;
    }

    public function getTemplateName() : string
    {
        return 'patterns/block-link.mustache';
    }
}
