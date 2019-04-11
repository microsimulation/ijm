<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use Microsimulation\Journal\Patterns\ViewModel;

interface ViewModelConverter
{
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel;

    public function supports($object, string $viewModel = null, array $context = []) : bool;
}
