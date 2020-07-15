<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use Microsimulation\Journal\Patterns\ViewModel;

class CategoryGroupConverter implements ViewModelConverter
{
    public function convert($object, string $viewModel = null, array $context = []): ViewModel
    {
        return new ViewModel\CategoryGroup();
    }

    public function supports($object, string $viewModel = null, array $context = []): bool
    {
        return true;
    }
}