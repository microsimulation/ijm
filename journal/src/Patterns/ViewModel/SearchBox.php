<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class SearchBox implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $compactForm;
    private $subjectFilter;

    public function __construct(CompactForm $compactForm, SubjectFilter $subjectFilter = null)
    {
        $this->compactForm = $compactForm;
        $this->subjectFilter = $subjectFilter;
    }

    public function getTemplateName() : string
    {
        return 'patterns/search-box.mustache';
    }
}
