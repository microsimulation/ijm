<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

class CategoryGroup implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    public function getTemplateName(): string
    {
        return 'patterns/collection-category-group.mustache';
    }
}