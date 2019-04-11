<?php

namespace Microsimulation\Journal\Helper;

use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;

trait HasViewModelConverter
{
    abstract protected function getViewModelConverter() : ViewModelConverter;
}
