<?php

namespace Microsimulation\Journal\ViewModel\Converter\Block;

use eLife\ApiSdk\Model\Block;
use Microsimulation\Journal\Helper\CanConvertContent;
use Microsimulation\Journal\Helper\HasViewModelConverter;
use Microsimulation\Journal\ViewModel\Converter\CreatesDoi;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Microsimulation\Journal\Patterns\PatternRenderer;
use Microsimulation\Journal\Patterns\ViewModel;

final class BoxConverter implements ViewModelConverter
{
    use CanConvertContent {
        convertTo as doConvert;
    }
    use CreatesDoi;
    use HasViewModelConverter;

    private $viewModelConverter;
    private $patternRenderer;

    public function __construct(ViewModelConverter $viewModelConverter, PatternRenderer $patternRenderer)
    {
        $this->viewModelConverter = $viewModelConverter;
        $this->patternRenderer = $patternRenderer;
    }

    /**
     * @param Block\Box $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        $level = $context['level'] ?? 1;

        return new ViewModel\Box(
            $object->getId(),
            $object->getLabel(),
            $object->getTitle(),
            $level,
            $this->createDoi($object),
            $this->patternRenderer->render(...$this->convertContent($object, $level + 1, $context))
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Block\Box;
    }

    protected function getViewModelConverter() : ViewModelConverter
    {
        return $this->viewModelConverter;
    }
}
