<?php

namespace Microsimulation\Journal\ViewModel\Converter\Block;

use eLife\ApiSdk\Model\Block;
use eLife\ApiSdk\Model\Footnote;
use Microsimulation\Journal\Helper\CanConvert;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Microsimulation\Journal\Patterns\PatternRenderer;
use Microsimulation\Journal\Patterns\ViewModel;

final class FigureAssetTableConverter implements ViewModelConverter
{
    use CanConvert;
    use CreatesAssetViewerInline;

    private $viewModelConverter;
    private $patternRenderer;

    public function __construct(ViewModelConverter $viewModelConverter, PatternRenderer $patternRenderer)
    {
        $this->viewModelConverter = $viewModelConverter;
        $this->patternRenderer = $patternRenderer;
    }

    /**
     * @param Block\FigureAsset $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        /** @var Block\Table $asset */
        $asset = $object->getAsset();

        $assetViewModel = new ViewModel\Table(
            $asset->getTables(),
            array_map(function (Footnote $footnote) {
                return new ViewModel\TableFootnote(
                    $this->getPatternRenderer()->render(...$footnote->getText()->map($this->willConvertTo())),
                    $footnote->getId(),
                    $footnote->getLabel()
                );
            }, $asset->getFootnotes())
        );

        return $this->createAssetViewerInline($object, $assetViewModel, null, null, $context);
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Block\FigureAsset && $object->getAsset() instanceof Block\Table;
    }

    protected function getPatternRenderer() : PatternRenderer
    {
        return $this->patternRenderer;
    }

    protected function getViewModelConverter() : ViewModelConverter
    {
        return $this->viewModelConverter;
    }
}
