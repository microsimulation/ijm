<?php

namespace Microsimulation\Journal\ViewModel\Converter\Block;

use eLife\ApiSdk\Model\Block;
use eLife\ApiSdk\Model\HasAttribution;
use eLife\ApiSdk\Model\HasCaption;
use Microsimulation\Journal\Helper\HasPatternRenderer;
use Microsimulation\Journal\Helper\HasViewModelConverter;
use Microsimulation\Journal\Patterns\ViewModel;
use Microsimulation\Journal\Patterns\ViewModel\Paragraph;

trait CreatesCaptionedAsset
{
    use HasPatternRenderer;
    use HasViewModelConverter;

    final private function createCaptionedAsset(ViewModel\IsCaptioned $viewModel, HasCaption $asset, ViewModel\Doi $doi = null) : ViewModel\CaptionedAsset
    {
        $caption = $asset->getCaption()->map(function (Block $block) {
            return $this->getViewModelConverter()->convert($block);
        });

        if ($asset instanceof HasAttribution && $asset->getAttribution()->notEmpty()) {
            $caption = $caption->append(...$asset->getAttribution()->map(function (string $attribution) {
                return new Paragraph($attribution);
            }));
        }

        if ($caption->notEmpty()) {
            $text = $this->getPatternRenderer()->render(...$caption);
        } else {
            $text = null;
        }

        if ($asset->getTitle()) {
            $captionText = ViewModel\CaptionText::withHeading($asset->getTitle(), null, $text);
        } elseif ($text) {
            $captionText = ViewModel\CaptionText::withText($text);
        } else {
            $captionText = null;
        }

        $inline = $asset instanceof Block\Image && $asset->isInline();

        return new ViewModel\CaptionedAsset($viewModel, $captionText, $doi, $inline);
    }
}
