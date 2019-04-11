<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class ReadMoreItem implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $item;
    private $content;
    private $isRelated;

    public function __construct(
        ContentHeaderReadMore $item,
        string $content = null,
        bool $isRelated = false
    ) {
        $this->item = $item;
        $this->content = $content;
        $this->isRelated = $isRelated;
    }

    public function getTemplateName() : string
    {
        return 'patterns/read-more-item.mustache';
    }
}
