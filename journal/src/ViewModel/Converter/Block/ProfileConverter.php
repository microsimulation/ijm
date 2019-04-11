<?php

namespace Microsimulation\Journal\ViewModel\Converter\Block;

use eLife\ApiSdk\Model\Block;
use Microsimulation\Journal\Helper\CanConvertContent;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Microsimulation\Journal\Patterns\PatternRenderer;
use Microsimulation\Journal\Patterns\ViewModel;

final class ProfileConverter implements ViewModelConverter
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
     * @param Block\Profile $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        $imageViewModel = $this->viewModelConverter->convert($object->getImage(), null, ['width' => 120, 'height' => 144]);

        return new ViewModel\InlineProfile(
            $imageViewModel,
            $this->patternRenderer->render(...$this->convertContent($object, $context['level'] ?? 1, $context))
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Block\Profile;
    }

    protected function getViewModelConverter() : ViewModelConverter
    {
        return $this->viewModelConverter;
    }
}
