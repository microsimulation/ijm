<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\Collection;
use Microsimulation\Journal\Helper\ModelName;
use Microsimulation\Journal\ViewModel\Factory\ContentHeaderImageFactory;
use Microsimulation\Journal\Patterns\ViewModel;
use Microsimulation\Journal\Patterns\ViewModel\Link;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function strip_tags;

final class CollectionContentHeaderConverter implements ViewModelConverter
{
    use CreatesDate;

    private $viewModelConverter;
    private $urlGenerator;
    private $contentHeaderImageFactory;

    public function __construct(ViewModelConverter $viewModelConverter, UrlGeneratorInterface $urlGenerator, ContentHeaderImageFactory $contentHeaderImageFactory)
    {
        $this->viewModelConverter = $viewModelConverter;
        $this->urlGenerator = $urlGenerator;
        $this->contentHeaderImageFactory = $contentHeaderImageFactory;
    }

    /**
     * @param Collection $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        $curatorName = $object->getSelectedCurator()->getDetails()->getPreferredName();
        if ($object->selectedCuratorEtAl()) {
            $curatorName .= ' et al.';
        }
        if ($object->getSelectedCurator()->getThumbnail()) {
            $curatorImage = $this->viewModelConverter->convert($object->getSelectedCurator()->getThumbnail(), null, ['width' => 48, 'height' => 48]);
        } else {
            $curatorImage = null;
        }

        return new ViewModel\ContentHeader(
            $object->getTitle(),
            $this->contentHeaderImageFactory->forImage($object->getBanner(), true), $object->getImpactStatement(), true, [], new ViewModel\Profile(new Link($curatorName), $curatorImage), [], [], null,
            new ViewModel\SocialMediaSharers(
                strip_tags($object->getTitle()),
                $this->urlGenerator->generate('collection', [$object], UrlGeneratorInterface::ABSOLUTE_URL)
            ),
            null,
            ViewModel\Meta::withLink(
                new Link(ModelName::singular('collection'), $this->urlGenerator->generate('collections')),
                $this->simpleDate($object, ['date' => 'published'] + $context)
            )
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Collection && ViewModel\ContentHeader::class === $viewModel;
    }
}
