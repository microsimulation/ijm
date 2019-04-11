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

final class FigureAssetImageConverter implements ViewModelConverter
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
        /** @var Block\Image $asset */
        $asset = $object->getAsset();
        $image = $asset->getImage();

        $assetViewModel = $this->viewModelConverter->convert($image, null, ['width' => 617]);

        $download = new ViewModel\Link('Download', $this->downloadLinkUriGenerator->generate(new DownloadLink($image->getSource()->getUri(), $image->getSource()->getFilename())));

        if ($image->getWidth() <= 1500 && $image->getHeight() <= 1500) {
            $openWidth = $image->getWidth();
            $openWidthActual = $openWidth;
            $openHeight = $image->getHeight();
            $openHeightActual = $openHeight;
        } elseif ($image->getWidth() >= $image->getHeight()) {
            $openWidth = 1500;
            $openWidthActual = $openWidth;
            $openHeight = null;
            $openHeightActual = (int) (1500 * ($image->getHeight() / $image->getWidth()));
        } else {
            $openWidth = null;
            $openWidthActual = (int) (1500 * ($image->getWidth() / $image->getHeight()));
            $openHeight = 1500;
            $openHeightActual = $openHeight;
        }

        $open = new ViewModel\OpenLink(
            $this->iiifUri($image, $openWidth, $openHeight),
            $openWidthActual,
            $openHeightActual
        );

        return $this->createAssetViewerInline($object, $assetViewModel, $download, $open, $context);
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Block\FigureAsset && $object->getAsset() instanceof Block\Image;
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
