<?php

namespace Microsimulation\Journal\ViewModel\Converter\Block;

use eLife\ApiSdk\Model\Block;
use Microsimulation\Journal\Helper\CreatesIiifUri;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Microsimulation\Journal\Patterns\PatternRenderer;
use Microsimulation\Journal\Patterns\ViewModel;

final class VideoConverter implements ViewModelConverter
{
    use CreatesCaptionedAsset;
    use CreatesIiifUri;

    private $viewModelConverter;
    private $patternRenderer;

    public function __construct(ViewModelConverter $viewModelConverter, PatternRenderer $patternRenderer)
    {
        $this->viewModelConverter = $viewModelConverter;
        $this->patternRenderer = $patternRenderer;
    }

    /**
     * @param Block\Video $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        $width = 639;
        if ($width > $object->getPlaceholder()->getWidth()) {
            $width = $object->getPlaceholder()->getWidth();
        }

        $video = new ViewModel\Video(
            array_map(function (Block\VideoSource $source) {
                return new ViewModel\MediaSource($source->getUri(), new ViewModel\MediaType($source->getMediaType()));
            }, $object->getSources()),
            $object->getPlaceholder() ? $this->iiifUri($object->getPlaceholder(), $width) : null,
            $object->isAutoplay(),
            $object->isLoop()
        );

        if (!$object->getTitle() && $object->getAttribution()->isEmpty() && $object->getCaption()->isEmpty()) {
            return $video;
        }

        return $this->createCaptionedAsset($video, $object);
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Block\Video;
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
