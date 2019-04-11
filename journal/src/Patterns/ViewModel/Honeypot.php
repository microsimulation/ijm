<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Microsimulation\Journal\Patterns\ComposedViewModel;
use Microsimulation\Journal\Patterns\ViewModel;

final class Honeypot implements ViewModel
{
    use ComposedViewModel;

    private $textField;

    public function __construct(TextField $textField)
    {
        $this->textField = $textField;
    }

    protected function getViewModel() : ViewModel
    {
        return $this->textField;
    }

    public function getTemplateName() : string
    {
        return 'patterns/honeypot.mustache';
    }
}
