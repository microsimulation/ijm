<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\Collection;
use Microsimulation\Journal\Helper\ModelName;
use Microsimulation\Journal\Patterns\ViewModel;
use Microsimulation\Journal\Patterns\ViewModel\Link;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function strip_tags;

final class CollectionContentHeaderConverter implements ViewModelConverter
{
    use CreatesDate;

    private $viewModelConverter;
    private $urlGenerator;

    public function __construct(ViewModelConverter $viewModelConverter, UrlGeneratorInterface $urlGenerator)
    {
        $this->viewModelConverter = $viewModelConverter;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Collection $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        return new ViewModel\ContentHeader(
            $object->getTitle(),
            null, $object->getImpactStatement(), true, [], null, [], [], null,
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
