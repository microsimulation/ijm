<?php

namespace Microsimulation\Journal\ViewModel\Converter\Block;

use eLife\ApiSdk\Model\Block;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Microsimulation\Journal\Patterns\ViewModel;

final class CodeConverter implements ViewModelConverter
{
    /**
     * @param Block\Code $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        return new ViewModel\Code($object->getCode());
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Block\Code;
    }
}
