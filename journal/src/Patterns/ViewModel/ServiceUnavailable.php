<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class ServiceUnavailable implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $button;

    public function __construct(Button $button = null)
    {
        $this->button = $button;
    }

    public function getTemplateName() : string
    {
        return 'patterns/service-unavailable.mustache';
    }
}
