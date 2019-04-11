<?php

namespace Microsimulation\Journal\ViewModel\Converter\Block;

use eLife\ApiSdk\Model\Block;
use Microsimulation\Journal\Helper\CanConvert;
use Microsimulation\Journal\Helper\CreatesIiifUri;
use Microsimulation\Journal\Helper\DownloadLink;
use Microsimulation\Journal\Helper\DownloadLinkUriGenerator;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Microsimulation\Journal\Patterns\PatternRenderer;
use Microsimulation\Journal\Patterns\ViewModel;

final class FigureAssetVideoConverter implements ViewModelConverter
{
    use CanConvert;
    use CreatesAssetViewerInline;
    use CreatesIiifUri;

    private $viewModelConverter;
    private $patternRenderer;
    private $downloadLinkUriGenerator;

    public function __construct(ViewModelConverter $viewModelConverter, PatternRenderer $patternRenderer, DownloadLinkUriGenerator $downloadLinkUriGenerator)
    {
        $this->viewModelConverter = $viewModelConverter;
        $this->patternRenderer = $patternRenderer;
        $this->downloadLinkUriGenerator = $downloadLinkUriGenerator;
    }

    /**
     * @param Block\FigureAsset $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        /** @var Block\Video $asset */
        $asset = $object->getAsset();

        $width = 639;
        if ($width > $asset->getPlaceholder()->getWidth()) {
            $width = $asset->getPlaceholder()->getWidth();
        }

        $assetViewModel = new ViewModel\Video(
            array_map(function (Block\VideoSource $source) {
                return new ViewModel\MediaSource($source->getUri(), new ViewModel\MediaType($source->getMediaType()));
            }, $asset->getSources()),
            $asset->getPlaceholder() ? $this->iiifUri($asset->getPlaceholder(), $width) : null,
            $asset->isAutoplay(),
            $asset->isLoop()
        );

        $download = new ViewModel\Link('Download', $this->downloadLinkUriGenerator->generate(DownloadLink::fromUri($asset->getSources()[0]->getUri())));

        return $this->createAssetViewerInline($object, $assetViewModel, $download, null, $context);
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Block\FigureAsset && $object->getAsset() instanceof Block\Video;
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
