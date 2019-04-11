<?php

namespace Microsimulation\Journal\ViewModel\Converter\Block;

use eLife\ApiSdk\Model\Block;
use Microsimulation\Journal\Helper\CanConvertContent;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Microsimulation\Journal\Patterns\PatternRenderer;
use Microsimulation\Journal\Patterns\ViewModel;

final class ExcerptConverter implements ViewModelConverter
{
    use CanConvertContent;

    private $viewModelConverter;
    private $patternRenderer;

    public function __construct(ViewModelConverter $viewModelConverter, PatternRenderer $patternRenderer)
    {
        $this->viewModelConverter = $viewModelConverter;
        $this->patternRenderer = $patternRenderer;
    }

    /**
     * @param Block\Excerpt $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        return new ViewModel\Quote(
            $this->patternRenderer->render(...$this->convertContent($object, 2, $context)),
            $object->getCite()
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Block\Excerpt;
    }

    protected function getViewModelConverter() : ViewModelConverter
    {
        return $this->viewModelConverter;
    }
}
