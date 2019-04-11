<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\CastsToArray;

final class FormLabel implements CastsToArray
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $labelText;
    private $isVisuallyHidden;

    public function __construct(string $labelText, bool $isVisuallyHidden = false)
    {
        $this->labelText = $labelText;
        $this->isVisuallyHidden = $isVisuallyHidden;
    }
}
