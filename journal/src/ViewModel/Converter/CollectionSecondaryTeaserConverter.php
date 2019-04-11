<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\Collection;
use Microsimulation\Journal\Helper\ModelName;
use Microsimulation\Journal\Patterns\ViewModel;
use Microsimulation\Journal\Patterns\ViewModel\Link;
use Microsimulation\Journal\Patterns\ViewModel\Meta;
use Microsimulation\Journal\Patterns\ViewModel\Teaser;
use Microsimulation\Journal\Patterns\ViewModel\TeaserFooter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CollectionSecondaryTeaserConverter implements ViewModelConverter
{
    use CreatesContextLabel;
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
        return Teaser::secondary(
            $object->getTitle(),
            $this->urlGenerator->generate('collection', [$object]),
            null,
            $this->createContextLabel($object),
            null,
            TeaserFooter::forNonArticle(
                Meta::withLink(new Link(ModelName::singular('collection'), $this->urlGenerator->generate('collections')), $this->simpleDate($object, $context))
            )
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Collection && ViewModel\Teaser::class === $viewModel && 'secondary' === ($context['variant'] ?? null);
    }

    protected function getViewModelConverter() : ViewModelConverter
    {
        return $this->viewModelConverter;
    }
}
