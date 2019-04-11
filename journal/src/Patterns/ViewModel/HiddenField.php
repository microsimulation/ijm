<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class HiddenField implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $name;
    private $id;
    private $value;

    public function __construct(string $name = null, string $id = null, string $value = null)
    {
        $this->name = $name;
        $this->id = $id;
        $this->value = $value;
    }

    public function getTemplateName() : string
    {
        return 'patterns/hidden-field.mustache';
    }
}
