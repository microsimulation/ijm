<?php

namespace Microsimulation\Journal\ViewModel\Converter\Block;

use eLife\ApiSdk\Model\Block;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Microsimulation\Journal\Patterns\ViewModel;
use Microsimulation\Journal\Patterns\ViewModel\Paragraph;

final class ParagraphConverter implements ViewModelConverter
{
    /**
     * @param Block\Paragraph $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        return new Paragraph($object->getText());
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Block\Paragraph;
    }
}
