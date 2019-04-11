<?php

namespace Microsimulation\Journal\ViewModel\Converter\Block;

use eLife\ApiSdk\Model\Block;
use eLife\ApiSdk\Model\Footnote;
use Microsimulation\Journal\Helper\CanConvert;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Microsimulation\Journal\Patterns\PatternRenderer;
use Microsimulation\Journal\Patterns\ViewModel;

final class TableConverter implements ViewModelConverter
{
    use CanConvert;
    use CreatesCaptionedAsset;

    private $viewModelConverter;
    private $patternRenderer;

    public function __construct(ViewModelConverter $viewModelConverter, PatternRenderer $patternRenderer)
    {
        $this->viewModelConverter = $viewModelConverter;
        $this->patternRenderer = $patternRenderer;
    }

    /**
     * @param Block\Table $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        $table = new ViewModel\Table(
            $object->getTables(),
            array_map(function (Footnote $footnote) {
                return new ViewModel\TableFootnote(
                    $this->patternRenderer->render(...$footnote->getText()->map($this->willConvertTo())),
                    $footnote->getId(),
                    $footnote->getLabel()
                );
            }, $object->getFootnotes())
        );

        if (!$object->getTitle() && $object->getAttribution()->isEmpty() && $object->getCaption()->isEmpty()) {
            return $table;
        }

        return $this->createCaptionedAsset($table, $object);
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Block\Table;
    }

    protected function getViewModelConverter() : ViewModelConverter
    {
        return $this->viewModelConverter;
    }

    protected function getPatternRenderer() : PatternRenderer
    {
        return $this->patternRenderer;
    }
}
