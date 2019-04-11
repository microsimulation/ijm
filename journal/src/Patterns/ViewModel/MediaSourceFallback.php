<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class MediaSourceFallback implements CastsToArray
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $content;
    private $isExternal;

    public function __construct(string $content, bool $isExternal = false)
    {
        Assertion::notBlank($content);

        $this->content = $content;
        $this->isExternal = $isExternal;
    }
}
