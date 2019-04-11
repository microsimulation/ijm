<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class ContentSource implements CastsToArray
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $contentType;
    private $text;

    public function __construct(Link $contentType, string $text = null)
    {
        $this->contentType = $contentType;
        $this->text = $text;
    }
}
