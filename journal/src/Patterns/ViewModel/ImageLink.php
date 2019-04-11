<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class ImageLink implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $url;
    private $image;

    public function __construct(string $url, Picture $image)
    {
        $this->url = $url;
        $this->image = $image;
    }

    public function getTemplateName() : string
    {
        return 'patterns/image-link.mustache';
    }
}
